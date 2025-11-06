<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            {{ __('Files for Employee') }}: <strong>{{ $employee->name }}</strong>
        </h1>
        <div>
            <a href="{{ route('admins.employees.show', $employee->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Employee') }}
            </a>
            <a href="{{ route('admins.employees.index') }}" class="btn btn-outline-secondary btn-sm ml-2">
                <i class="fas fa-list"></i> {{ __('All Employees') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-folder-open"></i>
                        {{ __('Employee Files') }}
                        <span class="badge badge-primary">{{ $employee->files->count() }}</span>
                    </h6>
                    @if($employee->files->count() > 0)
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadFileModal">
                                <i class="fas fa-plus"></i> {{ __('Upload New File') }}
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($employee->files->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="filesTable">
                                <thead class="thead-light">
                                <tr>
                                    <th width="15%">{{ __('Upload Date') }}</th>
                                    <th width="10%">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employee->files as $file)
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                {{ $file->created_at->format('d M, Y') }}<br>
                                                <span class="text-info">{{ $file->created_at->format('h:i A') }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admins.employees.files.download', ['employee' => $employee->id, 'file' => $file->id]) }}"
                                                   class="btn btn-outline-primary"
                                                   title="{{ __('Download') }}"
                                                   data-toggle="tooltip">
                                                    <i class="fas fa-download"></i>
                                                </a>

                                                    <button type="button"
                                                            class="btn btn-outline-info preview-file"
                                                            data-file-url="{{ Storage::disk('public')->url($file->path) }}"
                                                            title="{{ __('Preview') }}"
                                                            data-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                <form action="{{ route('admins.employees.files.delete', ['employee' => $employee->id, 'file' => $file->id]) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="{{ __('Delete') }}"
                                                            data-toggle="tooltip"
                                                            onclick="return confirm('{{ __('Are you sure you want to delete this file?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- File Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    {{ __('Total Files') }}
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employee->files->count() }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-file fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-folder-open fa-4x text-gray-300 mb-4"></i>
                                <h4 class="text-gray-500">{{ __('No files found') }}</h4>
                                <p class="text-gray-400 mb-4">{{ __('This employee has no files uploaded yet.') }}</p>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadFileModal">
                                    <i class="fas fa-plus"></i> {{ __('Upload First File') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">
                        <i class="fas fa-upload"></i> {{ __('Upload New File') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admins.employees.files.upload', $employee->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">{{ __('Select File') }}</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                            <small class="form-text text-muted">
                                {{ __('Maximum file size: 10MB') }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="uploadButton">
                            <i class="fas fa-upload"></i> {{ __('Upload File') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">{{ __('File Preview') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="filePreviewContent">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <a href="#" id="downloadPreviewFile" class="btn btn-primary">
                        <i class="fas fa-download"></i> {{ __('Download') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();

                // File preview functionality
                $('.preview-file').on('click', function() {
                    const fileUrl = $(this).data('file-url');
                    const fileType = $(this).data('file-type');
                    const fileName = $(this).data('file-name');

                    $('#filePreviewModalLabel').text(fileName);
                    $('#downloadPreviewFile').attr('href', fileUrl).attr('download', fileName);

                    let previewHtml = '';

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                        previewHtml = `<img src="${fileUrl}" class="img-fluid" alt="${fileName}" style="max-height: 70vh;">`;
                    } else if (fileType === 'pdf') {
                        previewHtml = `<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />`;
                    } else {
                        previewHtml = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ __("Preview not available for this file type. Please download the file to view it.") }}
                        </div>
                    `;
                    }

                    $('#filePreviewContent').html(previewHtml);
                    $('#filePreviewModal').modal('show');
                });

                // File upload form validation
                $('#uploadForm').on('submit', function() {
                    const fileInput = $('#file')[0];
                    if (fileInput.files.length > 0) {
                        const fileSize = fileInput.files[0].size;
                        const maxSize = 10 * 1024 * 1024; // 10MB

                        if (fileSize > maxSize) {
                            alert('{{ __("File size exceeds 10MB limit") }}');
                            return false;
                        }
                    }

                    $('#uploadButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __("Uploading...") }}');
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .empty-state {
                padding: 2rem 0;
            }
            .btn-group-sm > .btn {
                padding: 0.25rem 0.5rem;
                border-radius: 0.35rem;
            }
            .table th {
                border-top: none;
                background-color: #f8f9fc;
            }
            .preview-file:hover {
                transform: translateY(-1px);
                transition: transform 0.2s;
            }
            .card-border-left-primary {
                border-left: 0.25rem solid #4e73df !important;
            }
            .card-border-left-success {
                border-left: 0.25rem solid #1cc88a !important;
            }
        </style>
    @endpush
</x-dashboard.main-layout>
