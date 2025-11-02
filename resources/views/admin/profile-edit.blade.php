<x-dashboard.main-layout>

    <h1 class="mb-3 text-gray-800 h3">{{ __('Edit Profile') }}</h1>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admins.profile.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4 shadow card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Name *') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ Auth::user()->name }}" autofocus>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Email *') }}</label>
                                    <input type="text" name="email" class="form-control"
                                        value="{{ Auth::user()->email }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('Profile Image') }}</label>
                            <div>
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" id="existing-image" class="w_200" alt="">
                            </div>
                            <button type="button" id="remove-image" class="btn btn-outline-danger btn-sm">{{ __('Remove Image') }}</button>
                            <input type="file" name="image" accept="image/*" />
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#remove-image').click(function(){
                $('form').append('<input name="remove_image" type="hidden" value="1">');
                $('#existing-image').hide('slow'); 
            });
        });
    </script>


</x-dashboard.main-layout>
