@extends('backend.layouts.master')

@section('title')
    {{ translate('Creating New Ineractivity') }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('interactivity/ce-paste-theme.css') }}">
    <script src="{{ asset('interactivity/ce-helpers.js') }}" defer></script>
    <script>
        window.addEventListener('DOMContentLoaded', (_event) => {
             inputPrependBaseURL();
         });
    </script>

    <!-- INTERNATIONAL TELEPHONE INPUT -->
    <link href="{{ asset('interactivity/intlTelInput.css') }}" rel="stylesheet" />
    <script src="{{ asset('interactivity/intlTelInput.min.js') }}"></script>
    <!-- INTERNATIONAL TELEPHONE INPUT -->
    <!-- JSON EDITOR -->
    <link href="{{ asset('interactivity/jsoneditor.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('interactivity/jsoneditor.min.js') }}"></script>
    <script src="{{ asset('interactivity/flowtemplate.js') }}"></script>
    <!-- JSON EDITOR -->
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="{{ asset('interactivity/font-awesome.min.css') }}">
    <!-- FONT AWESOME -->
    <!-- ADDITIONAL STYLING -->
    <link href="{{ asset('interactivity/index.css') }}" rel="stylesheet" type="text/css">
    <!-- ADDITIONAL STYLING -->

<style>

    #network {
        white-space: pre-wrap; /* Allows text wrapping */
        font-family: 'Courier New', monospace;
        margin: 20px;
        padding: 20px;
        border: 1px solid #bbb;
        background-color: #f0f0f0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: #333;
        line-height: 1.5;
    }

    .tree-node {
        position: relative;
        margin-left: 20px;
    }

    .tree-node::before {
        content: '';
        position: absolute;
        top: 0;
        left: -10px;
        height: 100%;
        border-left: 2px dashed #888;
    }

    .tree-node::after {
        content: '';
        position: absolute;
        left: -10px;
        bottom: 50%;
        width: 10px;
        border-bottom: 2px dashed #888;
    }

    .tree-node:first-child::before {
        top: 50%;
    }

    .tree-node:last-child::before {
        height: 50%;
    }

    .tree-root {
        margin-left: 0;
    }

    .tree-root::before, .tree-root::after {
        display: none;
    }
  </style>
@endsection
    
@section('content')

    <div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">

            <section class="container-lg">
               <br>
               <div class="one container-lg">
                  <form>
                     <!-- GREETING INPUT -->
                     <label for="input-voice">{{ translate('Select Polly Voice!') }} <i class="fa fa-question-circle-o" title="{{ translate('This will adjust the voice of your greeting.') }}"></i></label>
                     <select id="input-voice" class="form-control">
                        <option value="Polly.Amy" selected>[Polly] Amy</option>
                        <option value="Polly.Emma">[Polly] Emma</option>
                        <option value="Polly.Brian">[Polly] Brian</option>
                        <option value="Polly.Amy-Neural">[Polly] Amy-Neural</option>
                        <option value="Polly.Emma-Neural">[Polly] Emma-Neural</option>
                        <option value="Polly.Brian-Neural">[Polly] Brian-Neural</option>
                     </select>
                     <label for="input-voice-greeting" class="mt-4">{{ translate('Configure your voice greeting') }} <i class="fa fa-question-circle-o" title="{{ translate('This will adjust your welcome message.') }}"></i></label>
                     <input type="text" class="form-control" id="input-voice-greeting" value="{{ translate('Hello! You have reached '. appName() .'. I am happy to help connect you to the correct co-working space department today. Please let me know who you need to speak with.') }}">
                     <!-- CHANNEL INPUT -->
                     <div>
                        <label for="branches" class="mt-4">{{ translate('Configure number of branches') }}: <i class="fa fa-question-circle-o" title="{{ translate('Set the number of channel') }}"></i></label>
                        <select id="branches">
                           <option value=0>{{ translate('0') }}</option>
                           <option value=1 selected>{{ translate('1') }}</option>
                           <option value=2>{{ translate('2') }}</option>
                           <option value=3>{{ translate('3') }}</option>
                           <option value=4>{{ translate('4') }}</option>
                           <option value=5>{{ translate('5') }}</option>
                           <option value=6>{{ translate('6') }}</option>
                           <option value=7>{{ translate('7') }}</option>
                           <option value=8>{{ translate('8') }}</option>
                           <option value=9>{{ translate('9') }}</option>
                           <option value=10>{{ translate('10') }}</option>
                        </select>
                        <div class="btn-container-wrapper">
                           <div class="btn-container">
                              <a class="btn" target="_blank" onclick="build_branches()">{{ translate('Add Channel') }}</a>
                           </div>
                        </div>
                     </div>
                     <!-- CHANNEL INPUT -->
                     <div id="emptyChannelMessage" style="margin-top: 20px;" class="alert alert-warning">
                        {{ translate('If you already added to the Twilio Studio, you do not need to add a channel here anymore.') }}
                     </div>
                     <!-- CHANNEL CHECK INPUT -->
                     <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">

                        <thead>
                           <tr class="nk-tb-item nk-tb-head">
                              <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CHANNEL') }}</span></th>
                              <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CHANNEL NAME') }}</span></th>
                              <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CHANNEL PHONE') }}</span></th>
                              <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                              </th>
                           </tr>
                        </thead>
                        
                        <tbody>
                           <tr class="branch-rows nk-tb-item" id="branch-row-0">
                              <td class="nk-tb-col">{{ translate('0') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch0" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch0num"></td>
                              <td class="nk-tb-col">
                                 <button type="button" onclick="removeBranch('branch-row-0')">{{ translate('Remove') }}</button>
                              </td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('1') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch1" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch1num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('2') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch2" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch2num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('3') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch3" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch3num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('4') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch4" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch4num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('5') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch5" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch5num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('6') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch6" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch6num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('7') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch7" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch7num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('8') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch8" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch8num"></td>
                           </tr>
                           <tr class="branch-rows nk-tb-item">
                              <td class="nk-tb-col">{{ translate('9') }}</td>
                              <td class="nk-tb-col"><input type="text" id="branch9" value="{{ translate('Insert channel name...') }}"></td>
                              <td class="nk-tb-col"><input type="text" id="branch9num"></td>
                           </tr>
                        </tbody>
                     </table>
                     <!-- BRANCH CHECK INPUT -->

                     <div id="network"></div>


                     <!-- PUSH TO JSON -->
                     <div class="btn-container-wrapper">
                        <div class="btn-container">
                           <a class="btn" target="_blank" onclick="save_to_flow_and_copy()">{{ translate('Save & Copy flow to clipboard') }}</a>
                        </div>
                     </div>
                     <!-- PUSH TO JSON -->
                     <!-- CLIPBOARD STATUS -->
                     <p id="clipboardStatusMessage"></p>
                     <!-- CLIPBOARD STATUS -->
                  </form>
               </div>
               <!-- JSON EDITOR FORM -->
               <!-- JSON EDITOR -->
               <div class="two">
                  <div id="jsoneditor" class="d-none"></div>
                  <script>
                     const container = document.getElementById("jsoneditor");
                     const options = {
                         mode: 'code'
                     }
                     const editor = new JSONEditor(container, options)
                     editor.set(initialJSON)
                     // get json from editor
                     const updatedJson = editor.get()
                  </script>
               </div>
               <!-- JSON EDITOR -->
            </section>

        </div>
    </div><!-- .card-preview -->
</div>
<!-- END: Large Slide Over Toggle -->   

@endsection

@section('js')
    <script src="{{ asset('interactivity/index.js') }}"></script>
@endsection