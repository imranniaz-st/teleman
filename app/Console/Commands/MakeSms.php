<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Contact;
use Twilio\Rest\Client;
use App\Models\Campaign;
use App\Models\SmsContent;
use App\Models\SmsSchedule;
use Illuminate\Console\Command;
use Log;

class MakeSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will manage schedule sms';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This PHP function finds a contact by their ID using the Contact model.
     * 
     * @param contact_id The parameter `` is an integer representing the unique identifier
     * of a contact in a database. The function `find_contact` uses this parameter to query the
     * `Contact` model and retrieve the first record that matches the given `id`.
     * 
     * @return The function `find_contact` is returning a single `Contact` model instance that matches
     * the given ``.
     */
    public function find_contact($contact_id)
    {
        return Contact::where('id', $contact_id)
                    ->first();
    }

    /**
     * The function replaces shortcodes in a given text with corresponding values from a data array.
     * 
     * @param text The text string that contains the shortcodes to be replaced with data values.
     * @param data The  parameter is an array that contains the values to replace the shortcodes
     * with. The keys of the array correspond to the shortcode names. For example, if there is a
     * shortcode {name} in the  parameter, then the  array should have a key 'name' with the
     * 
     * @return the updated text with the shortcodes replaced by their corresponding values from the
     * provided data array.
     */
    public function replaceShortcodes($text, $data) {
        // Define the shortcode pattern
        $pattern = '/\{(\w+)\}/';

        // Extract the shortcodes from the textarea
        preg_match_all($pattern, $text, $matches);
        $shortcodes = $matches[0];
        $keys = $matches[1];

        // Retrieve the data for each shortcode
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $value = $data[$key];
                $text = str_replace("{{$key}}", $value, $text);
            }
        }

        return $text;
    }

    /**
     * This PHP function retrieves the content of a specific SMS campaign.
     * 
     * @param campaign_id The campaign_id parameter is the unique identifier of a specific SMS
     * campaign. It is used to retrieve the content of the SMS campaign from the database.
     * 
     * @return the content of the SMS campaign with the given campaign ID. If there is no content found
     * for the given campaign ID, it will return null.
     */
    public function campaign_content($campaign_id)
    {
        return SmsContent::where('campaign_id', $campaign_id)->first()->content ?? null;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            echo 'Started at: ' . Carbon::now() . PHP_EOL;

            // Define the number of records to retrieve per page
            $perPage = 500; // Adjust this to your desired batch size

                $currentPage = 1; // Reset current page for each campaign

                $campaign = SmsSchedule::where('status', 'PENDING')
                            ->where('start_at', '<=', Carbon::now())
                            ->with(['contacts' => function ($query) use ($currentPage, $perPage) {
                                $query->skip(($currentPage - 1) * $perPage)->take($perPage);
                            }])
                            ->first();

                if ($campaign == null) {
                    echo 'No Campaign is scheduled'.PHP_EOL;
                    return false; // Break out of the loop if no campaign is found
                }

                // Checking balance and other validations
                if (check_balance($campaign->user_id) == false) {
                    echo 'Insufficient balance'.PHP_EOL;
                    return;
                }

                if ($campaign->group_id == null || $campaign->provider == null) {
                    echo 'Campaign has no group or provider'.PHP_EOL;
                    return false;
                }

                if ($campaign->contacts->count() == 0) {
                    echo 'No Contacts found'.PHP_EOL;
                    return false;
                }

                if (check_twilio_connection(account_sid($campaign->provider)) == false && $campaign->third_party_provider == null) {
                    echo 'Twilio Connection Failed'.PHP_EOL;
                    return false;
                }

                echo 'Contacts: '.$campaign->contacts->count().PHP_EOL;

                $content = $this->campaign_content($campaign->campaign_id);

                $accountSid = user_provider_info($campaign->user_id)->account_sid;
                $authToken  = user_provider_info($campaign->user_id)->auth_token;
                $twilio = new Client($accountSid, $authToken);

                    foreach ($campaign->contacts as $contact) {
                        $content_data = [
                            'name' => $this->find_contact($contact->contact_id)->name,
                            'phone' => $this->find_contact($contact->contact_id)->phone,
                            'country' => $this->find_contact($contact->contact_id)->country,
                            'profession' => $this->find_contact($contact->contact_id)->profession,
                        ];

                        $dynamic_content = $this->replaceShortcodes($content, $content_data);

                        if ($campaign->third_party_provider == null) {
                            $twilio->messages->create(
                                '+' . phone_number($contact->contact_id),
                                [
                                    'from' => provider_phone($campaign->provider),
                                    'body' => $dynamic_content,
                                ]
                            );
                        } elseif ($campaign->third_party_provider == 'mojsms') {
                            mojSMSender(phone_number($contact->contact_id), $dynamic_content, $campaign->user_id);
                        }

                        store_to_messages(phone_number($contact->contact_id), $dynamic_content, $campaign->user_id, $campaign->campaign_id);
                        echo 'Sending SMS To: ' . phone_number($contact->contact_id) . PHP_EOL;
                    }

                    $totalContacts = $campaign->contacts()->count();
                    $processedContacts = $currentPage * $perPage;

                    if ($processedContacts >= $totalContacts) {

                        // Update the campaign status
                        $campaign->status = 'COMPLETED';
                        $campaign->save();

                        echo 'Completed at: ' . Carbon::now() . PHP_EOL;
                        CronJob('start:sms', 1, null);

                        return false; // Break out of the inner loop if all contacts are processed
                    }

                    $currentPage++; // Move to the next page

                    // Refresh campaign contacts for the next batch
                    $campaign->load(['contacts' => function ($query) use ($currentPage, $perPage) {
                        $query->skip(($currentPage - 1) * $perPage)->take($perPage);
                    }]);

        } catch (\Throwable $th) {
            CronJob('start:sms', 0, $th->getMessage());
            Log::info($th);
        }
    }

}
