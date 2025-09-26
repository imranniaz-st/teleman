<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {

    /* This route is defining a URL pattern for accessing the messages page in the messaging system. It
    maps the URL to the "index" method in the "MessageController" class and gives it the name
    "message.index". */
    Route::get('messages', [MessageController::class, 'index'])->name('message.index');

    Route::get('messages/ajax/{my_number?}', [MessageController::class, 'messages_ajax_fetch'])->name('message.ajax.fetch');

    /* This route is defining a URL pattern for accessing a conversation between two users in the
    messaging system. The URL will include two parameters: "my_number" and "user_number". The "?"
    after "user_number" indicates that this parameter is optional. The route is mapped to the
    "message" method in the "MessageController" class and is given the name "message.conversations". */
    Route::get('message/{my_number}/{user_number?}', [MessageController::class, 'message'])->name('message.conversations');
    Route::get('message/delete/{my_number}/{user_number?}', [MessageController::class, 'destroy'])->name('message.delete');

    // Route for sending a message
    Route::post('/chat/send/{my_number?}/{user_number}', [MessageController::class, 'send'])->name('messages.send');

    Route::post('/compose/send/{my_number}', [MessageController::class, 'compose_new_message'])->name('messages.compose_new_message');

    // Route for retrieving messages
    Route::get('/chat/messages/{my_number}/{user_number?}', [MessageController::class, 'show'])->name('messages.show'); // AJAX Route
    Route::get('/refresh-messages/{my_number}', [MessageController::class, 'refreshMessages'])->name('refresh-messages'); // AJAX Route

});

// Route for processing incoming Twilio messages
Route::post('/handle_message', [MessageController::class, 'processIncomingMessage']);