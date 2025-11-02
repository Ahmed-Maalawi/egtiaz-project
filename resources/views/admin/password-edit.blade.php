<x-dashboard.main-layout>

    <h1 class="mb-3 text-gray-800 h3">{{ __('Edit Profile') }}</h1>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admins.password.update') }}" method="post" enctype="application/x-www-form-urlencoded">
                @csrf
                @method('PUT')
                <div class="mb-4 shadow card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Old Password *') }}</label>
                                    <input type="password" name="old_password" class="form-control" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('New Password *') }}</label>
                                    <input type="password" name="new_password" class="form-control" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Retype New Password *') }}</label>
                                    <input type="password" name="re_new_password" class="form-control" >
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



</x-dashboard.main-layout>