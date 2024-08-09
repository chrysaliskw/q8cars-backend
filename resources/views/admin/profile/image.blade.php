<div class="row">
    <div class="col-md-4">
        <div class="form-group">
        
            <label for="picture" class="control-label">Profile Picture</label><br>
                @if($admin->picture)
                
                   <img id="profile_pic" src="{{ file_asset('files-admin', $admin->picture) }}" 
                        alt="profile-img" class="img-thumbnail" width="200" height="150" />
                        
                    <input id="picture" type="file" name="picture" class="form-control">
                        <span class="error" role="alert">
                            @error('picture')
                                {{ $message }}</br>
                            @enderror
                        </span>
           
                @else
               
                    <img  id="profile_pic" src="{{ asset('moltran-asset/images/dp.png') }}" alt="profile-img" class="img-thumbnail" width="200" height="200">
                    <input id="picture" type="file" name="picture" class="form-control" onchange="loadFile(this)">
                        <span class="error" role="alert">
                            @error('picture')
                                {{ $message }}</br>
                            @enderror
                        </span>
           
                @endif
        </div>     
    </div>
</div>

    <script type="application/javascript">
    
        function loadFile(picture) 
            {
	            var image = document.getElementById('profile_pic');
                image.src = URL.createObjectURL(event.target.files[0]);
            }
    </script>


            