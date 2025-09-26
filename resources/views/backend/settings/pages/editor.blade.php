@extends('backend.layouts.master')

@section('css')
<link href="{{ asset('editorjs/assets/demo.css') }}" rel="stylesheet">
<script src="{{ asset('editorjs/assets/json-preview.js') }}"></script>
@endsection

@section('title')
@if(Route::currentRouteName() == 'dashboard.page.edit')
  {{ translate('Modifying') }} - 
@endif
    {{ $editor->page_name ?? translate('Page Name') }}
@endsection

@section('content')
<div class="container-fluid py-4">

@if(Route::currentRouteName() == 'dashboard.page.edit')
  <label class="form-label" for="pageName">{{ translate('Blog/Page Title') }}</label>
  <input type="text" id="pageName" value="{{ $editor->page_name ?? null }}" class="form-control" placeholder="Blog/Page Name">
  @else
  <input type="hidden" id="pageName" value="{{ $editor->page_name ?? null }}" class="form-control" placeholder="Blog/Page Name">
@endif

<div class="ce-example">

    <div class="ce-example__content _ce-example__content--small">
      <div id="editorjs"></div>

      <div class="ce-example__button" id="storeBlock">
        {{ translate('Save Changes') }}
      </div>

      <div class="ce-example__statusbar">
        {{ translate('Readonly') }}:
        <b id="readonly-state">
          {{ translate('Off') }}
        </b>
        <div class="ce-example__statusbar-button" id="toggleReadOnlyButton">
          {{ translate('toggle') }}
        </div>
      </div>
    </div>

  </div>

</div>

@endsection

@section('js')

  <script src="{{ asset('editorjs/js/jquery.js') }}"></script>
  <script src="{{ asset('editorjs/js/header@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/simple-image@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/delimiter@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/list@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/checklist@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/quote@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/code@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/embed@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/table@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/link@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/warning@latest.js') }}"></script>

  <script src="{{ asset('editorjs/js/marker@latest.js') }}"></script>
  <script src="{{ asset('editorjs/js/inline-code@latest.js') }}"></script>

  <!-- Load Editor.js's Core -->
  <script src="{{ asset('editorjs/js/editorjs@latest.js') }}"></script>

  <!-- Initialization -->
  <script>

    "use strict";

    /**
     * To initialize the Editor, create a new instance with configuration object
     * @see docs/installation.md for mode details
     */
    var editor = new EditorJS({
      /**
       * Enable/Disable the read onlyll mode
       */
      readOnly: false,

      /**
       * Wrapper of Editor
       */
      holder: 'editorjs',

      /**
       * Common Inline Toolbar settings
       * - if true (or not specified), the order from 'tool' property will be used
       * - if an array of tool names, this order will be used
       */
      // inlineToolbar: ['link', 'marker', 'bold', 'italic'],
      // inlineToolbar: true,

      /**
       * Tools list
       */
      tools: {
        /**
         * Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
         */
        header: {
          class: Header,
          inlineToolbar: ['marker', 'link'],
          config: {
            placeholder: 'Header'
          },
          shortcut: 'CMD+SHIFT+H'
        },

        /**
         * Or pass class directly without any configuration
         */
        image: SimpleImage,

        list: {
          class: List,
          inlineToolbar: true,
          shortcut: 'CMD+SHIFT+L'
        },

        checklist: {
          class: Checklist,
          inlineToolbar: true,
        },

        quote: {
          class: Quote,
          inlineToolbar: true,
          config: {
            quotePlaceholder: 'Enter a quote',
            captionPlaceholder: 'Quote\'s author',
          },
          shortcut: 'CMD+SHIFT+O'
        },

        warning: Warning,

        marker: {
          class:  Marker,
          shortcut: 'CMD+SHIFT+M'
        },

        code: {
          class:  CodeTool,
          shortcut: 'CMD+SHIFT+C'
        },

        delimiter: Delimiter,

        inlineCode: {
          class: InlineCode,
          shortcut: 'CMD+SHIFT+C'
        },

        linkTool: LinkTool,

        embed: Embed,

        table: {
          class: Table,
          inlineToolbar: true,
          shortcut: 'CMD+ALT+T'
        },

      },

      /**
       * This Tool will be used as default
       */
      // defaultBlock: 'paragraph',

      /**
       * Initial Editor data
       */
      data: {
        @if($editor->blocks == null)
        
        blocks: [
            {
                "id" : "k4rLS9Zl3y",
                "type" : "header",
                "data" : {
                    "text" : '{{ $editor->page_name ?? "Page Name" }}',
                    "level" : 2
                }
            }
        ],
        @else
        blocks: {!! $editor->blocks ?? '[]' !!}
        @endif
      },
      onChange: function(api, event) {
        // 'Editor was changed'
      }
    });

    /**
     * Saving button
     */
    const saveButton = document.getElementById('saveButton');

    /**
     * Toggle read-only button
     */
    const toggleReadOnlyButton = document.getElementById('toggleReadOnlyButton');
    const readOnlyIndicator = document.getElementById('readonly-state');

    /**
     * Toggle read-only example
     */
    toggleReadOnlyButton.addEventListener('click', async () => {
      const readOnlyState = await editor.readOnly.toggle();

      readOnlyIndicator.textContent = readOnlyState ? 'On' : 'Off';
    });

    

    // ajax query
    $('#storeBlock').on('click', function(){
        editor.save()
        .then((savedData) => {

          var pageName = $('#pageName').val();

          //check if page name is empty
          if(pageName == ''){
            var name = '{{ $editor->page_name ?? null }}';
          }else{
            var name = $('#pageName').val();
          }
  
            // ajax setup

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

            // ajax setup request start

            $.ajax({
            type: 'POST',
            url: '{{ route('projects.editorjs.store', [$id, $slug]) }}',
            data: {
                id: '{{ $editor->id }}',
                page_name: name,
                menu_item: '{{ $editor->menu_item ?? null }}',
                status: '{{ $editor->status ?? 1 }}',
                blocks: JSON.stringify(savedData.blocks)
            },
            beforeSend: function(){
                toastr.info('please wait...');
              },
              success: function(data) {
                toastr.remove();
                toastr.success('saved');
              }
            });

            // ajax setup request end

        })
        .catch((error) => {
          toastr.remove();
          toastr.error('saving error');
        });
    });

  </script>
@endsection