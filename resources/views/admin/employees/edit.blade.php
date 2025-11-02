<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.employees.update', $employee->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') ?? $employee->name }}">
            </div>

            <div class="form-group">
                <label for="company">{{ __('Company') }}</label>
                <select name="company_id" id="select_company" style="width: 100%">
                    <option value="{{ $employee->company_id }}">
                        {{ $employee->company->getTranslation('name', app()->getLocale()) }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="type">{{ __('Iqama Type') }}</label>
                <select name="type_id" class="select2">
                    @foreach ($iqamaTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('type_id') == $type->id || $employee->iqama_type_id == $type->id)>
                            {{ $type->getTranslation('name', app()->getLocale()) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" value="{{ old('email') ?? $employee->email }}">
            </div>

            <div class="form-group">
                <label for="phone">{{ __('Phone') }}</label>
                <input type="text" name="phone" class="form-control" id="phone"
                    placeholder="{{ __('Phone') }}" value="{{ old('phone') ?? $employee->phone }}">
            </div>

            <div class="form-group">
                <label for="address">{{ __('Address') }}</label>
                <textarea name="address" class="form-control" id="address" placeholder="{{ __('Address') }}">{{ old('address') ?? $employee->address }}</textarea>
            </div>

            <div class="form-group">
                <label for="gender">{{ __('gender') }}</label>
                <select name="gender" class="form-control select2" id="gender" required>
                    <option value="m" {{ old('gender') == 'm' || $employee->gender == 'm' ? 'selected' : '' }}>
                        {{ __('Male') }}</option>
                    <option value="f" {{ old('gender') == 'f' || $employee->gender == 'f' ? 'selected' : '' }}>
                        {{ __('Female') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control select2" id="status" required>
                    <option value="active"
                        {{ old('status') == 'active' || $employee->status == 'active' ? 'selected' : '' }}>
                        {{ __('Active') }}
                    </option>
                    <option value="inactive"
                        {{ old('status') == 'inactive' || $employee->status == 'inactive' ? 'selected' : '' }}>
                        {{ __('Inactive') }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="passport_number">{{ __('Passport Number') }}</label>
                <input type="text" name="passport_number" class="form-control" id="passport_number"
                    placeholder="{{ __('Passport Number') }}"
                    value="{{ old('passport_number') ?? $employee->passport_number }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Passport Image') }}</label>
                <div>
                    @if ($employee->passport_image)
                        <img src="{{ asset('storage/' . $employee->passport_image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="passport_image" class="for">{{ __('Passport Image') }}</label>
                <input type="file" name="passport_image" class="form-control" id="passport_image"
                    placeholder="{{ __('Passport Image') }}">
            </div>
            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($employee->image)
                        <img src="{{ asset('storage/' . $employee->image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <div class="form-group">
                <label for="files" class="for">{{ __('Attach Files') }}</label>

                <input type="file" name="files[]" id="files" class="form-control" multiple>

                <ul id="existing-file-list" class="list-group mt-2">
                    @foreach ($employee->files as $file)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                @if (Str::startsWith($file->mime, 'image/'))
                                    <img src="{{ asset('storage/' . $file->path) }}" class="img-thumbnail me-2"
                                        style="width:50px;height:50px;object-fit:cover;">
                                @else
                                    <i class="bi bi-file-earmark-text me-2" style="font-size:1.5rem;"></i>
                                @endif
                                <span>{{ basename($file->path) }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-existing-file"
                                data-id="{{ $file->id }}">×</button>
                        </li>
                    @endforeach
                </ul>

                <ul id="file-list" class="list-group mt-2"></ul>

                <small class="form-text text-muted">{{ __('You can add new files or remove old ones') }}</small>
            </div>

            <input type="hidden" name="removed_files" id="removed_files">

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            $('#select_company').select2({
                placeholder: "{{ __('Type A Company Name') }}",
                ajax: {
                    url: "{{ route('admins.companies.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(company => ({
                                id: company.id,
                                text: company.name,
                            }))
                        };
                    },
                    cache: true,
                }
            })
        });
        $(document).ready(function() {
            let fileList = []; // new files
            let removedFiles = []; // existing files to delete

            // Handle NEW file selection
            $('#files').on('change', function(e) {
                let newFiles = Array.from(e.target.files);
                fileList = fileList.concat(newFiles);
                renderFileList();
            });

            function renderFileList() {
                $('#file-list').empty();

                fileList.forEach((file, index) => {
                    let isImage = file.type.startsWith('image/');
                    let preview = isImage ?
                        `<img src="${URL.createObjectURL(file)}" class="img-thumbnail me-2" style="width:50px;height:50px;object-fit:cover;">` :
                        `<i class="bi bi-file-earmark-text me-2" style="font-size:1.5rem;"></i>`;

                    $('#file-list').append(`
                <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        ${preview}
                        <span>${file.name} (${Math.round(file.size/1024)} KB)</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-file" data-index="${index}">×</button>
                </li>
            `);
                });

                updateFileInput();
            }

            // Remove NEW file
            $(document).on('click', '.remove-file', function() {
                let index = $(this).data('index');
                fileList.splice(index, 1);
                renderFileList();
            });

            // Sync file input with DataTransfer
            function updateFileInput() {
                let dataTransfer = new DataTransfer();
                fileList.forEach(file => dataTransfer.items.add(file));
                $('#files')[0].files = dataTransfer.files;
            }

            // Remove EXISTING file
            $(document).on('click', '.remove-existing-file', function() {
                let id = $(this).data('id');
                removedFiles.push(id);

                // update hidden input
                $('#removed_files').val(JSON.stringify(removedFiles));

                $(this).closest('li').remove();
            });
        });
    </script>

</x-dashboard.main-layout>
