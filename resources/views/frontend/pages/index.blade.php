@extends('frontend.titania.layouts.master')

@section('title')
    {{ $page->page_name ?? appName() }}
@endsection

@section('css')
<link href="{{ asset('editorjs/assets/demo.css') }}" rel="stylesheet">
<script src="{{ asset('editorjs/assets/json-preview.js') }}"></script>
@endsection

@section('content')

<!--Nav-->
@includeWhen(true, 'frontend.titania.components.nav')
<!--Nav::END-->

<div class="ce-example">
    <div class="ce-example__content _ce-example__content--small">
        <div id="editorjs"></div>
    </div>
</div>
@endsection

@section('js')

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
            readOnly: true,

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
                    class: Marker,
                    shortcut: 'CMD+SHIFT+M'
                },

                code: {
                    class: CodeTool,
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
                blocks: {!! $page->blocks ?? '[]' !!}
            },
        
        });

    </script>
@endsection