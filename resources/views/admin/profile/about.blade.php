<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $admin->name }}">
            <span class="error" role="alert">
            @error('name')
                {{ $message }}</br>
            @enderror
            </span>
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $admin->email }}" >
            <span class="error" role="alert">
            @error('email')
                {{ $message }}</br>
            @enderror
            </span>
        </div>
    </div>
</div>