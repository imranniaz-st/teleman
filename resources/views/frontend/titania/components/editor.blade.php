<textarea id="log" class="d-none"></textarea>

@auth
      
@can('admin')


  <script type="text/javascript">

        "use strict";
  
        // Editable
        function Editable(sel, options) {
            if (!(this instanceof Editable)) return new Editable(...arguments);

            const attr = (EL, obj) => Object.entries(obj).forEach(([prop, val]) => EL.setAttribute(prop, val));

            Object.assign(this, {
                onStart() {},
                onInput() {},
                onEnd() {},
                classEditing: "is-editing", // added onStart
                classModified: "is-modified", // added onEnd if content changed
            }, options || {}, {
                elements: document.querySelectorAll(sel),
                element: null, // the latest edited Element
                isModified: false, // true if onEnd the HTML content has changed
            });

            const start = (ev) => {
                this.isModified = false;
                this.element = ev.currentTarget;
                this.element.classList.add(this.classEditing);
                this.text_before = ev.currentTarget.textContent;
                this.html_before = ev.currentTarget.innerHTML;
                this.onStart.call(this.element, ev, this);
            };

            const input = (ev) => {
                this.text = this.element.textContent;
                this.html = this.element.innerHTML;
                this.isModified = this.html !== this.html_before;
                this.element.classList.toggle(this.classModified, this.isModified);
                this.onInput.call(this.element, ev, this);
            }

            const end = (ev) => {
                this.element.classList.remove(this.classEditing);
                this.onEnd.call(this.element, ev, this);
            }

            this.elements.forEach(el => {
                attr(el, {
                    tabindex: 1,
                    contenteditable: true
                });
                el.addEventListener("focusin", start);
                el.addEventListener("input", input);
                el.addEventListener("focusout", end);
            });

            return this;
        }

        // Use like:
        Editable(".editable", {
            onEnd(ev, UI) { // ev=Event UI=Editable this=HTMLElement
                if (!UI.isModified) return; // No change in content. Abort here.
                const data = {
                    cid: this.dataset.cid,
                    text: this.textContent, // or you can also use UI.text
                }

                var obj = {
                  cid: data.cid,
                  text: data.text
                };

                $.ajax({
                    type: "GET",
                    url: "{{ route('frontend.json.editor') }}",
                    data: obj,
                    success: function(res) {
                        toastr.success('Successfully updated');
                    }
                });

            }
        });


        
    </script>

    <script>
        
        for (let index = 1; index < 40; index++) {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview' + index).css('background-image', 'url('+ e.target.result +')');
                        $('#imagePreview' + index).hide();
                        $('#imagePreview' + index).fadeIn(650);
                        var dataImg = $('.liveImagePreview' + index).attr('data-img');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });


                        $.ajax({
                        type: 'POST',
                        url: '{{ route("frontend.json.upload") }}',
                        data: {
                            cid: dataImg,
                            text: e.target.result
                        },
                        success: function(data) {
                            toastr.success('Successfully uploaded');
                        }
                        });


                    }
                    reader.readAsDataURL(input.files[0]);

                }
            }

            $("#imageUpload" + index).change(function() {
                readURL(this);
            });
        }
        
    </script>


  @endcan
  
  @endauth
