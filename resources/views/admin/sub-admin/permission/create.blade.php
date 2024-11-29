<x-admin-layout title="Permissions">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.permission.index') }}">Permissions</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Permission">
    <x-form method="POST" action="{{ route('admin.sub-admin.permission.store') }}" class="form" >
            <div class="row">
                <div class="col-md-3">
                    <x-form-select field="section" id="section" field-name="Section" defaultPrompt="Select section" >
                        @foreach(config('params.sub-admin.sections') as $value => $label)
                            <option {{ old("section") == $value ? "Selected" : "" }} value="{{ $label }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-3">
                    <x-form-input type="text" id="name" field="name" field-name="Name" value="{{ old('name') }}" readonly>
                    </x-form-input>
                </div>
                {{-- <input type="hidden" value="0" name="subsectionCheck" id="subsectionCheck"/> --}}
            </div>


            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    <x-slot name="scripts">
        {{-- <script type="application/javascript">
            $( document ).ready(function() {
                var section = document.getElementById("section").value;
            });

            $.ajax({
                    url : '{{ route("admin.sub-admin.permission.get-subsections") }}',
                    Type : 'GET',
                    data : {
                        section : section,
                    },
                    success : function(data) {
                        console.log('success');
                        console.log(data);
                        opsub+= '<option value="" Selected disabled>Select sub section</option>';
                        $('#subsection').html('');
                        if(data.length > 0) {
                            for(var i=0; i<data.length;i++){
                                 opsub+= '<option value="'+data[i]+'">'+data[i]+'</option>';
                            }
                            $('#subsection').html(opsub);
                            document.getElementById("subsectionCheck").value = 1;
                        }
                        else {
                            document.getElementById("subsectionDiv").style.display = "none";
                            document.getElementById("name").value = section;
                            document.getElementById("subsectionCheck").value = 0;
                        }
                    },
                    error:function() {
                    }
                });

            function setPermissionName(that)
            {
                var subsection = that.value;
                var permission = document.getElementById("section").value;
                if(permission != null) {
                    permission = permission +' '+ subsection;
                }
                else {
                    permission = subsection;
                }
                document.getElementById("name").value = permission;
            } --}}

            <script type="application/javascript">
                $(document).ready(function () {
                    $('#section').on('change', function () {
                        var section = $(this).val();
                        if (section) {
                            $('#name').val(section);
                        } else {
                            $('#name').val('');
                        }
                    });
                });

        </script>
    </x-slot>

</x-admin-layout>
