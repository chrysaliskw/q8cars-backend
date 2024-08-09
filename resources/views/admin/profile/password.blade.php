<div class="row">
    <div class="col-md-4">
        <div class="form-group">
       
            <label for="password" class="control-label">Current Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
                <div class="input-group-append">
                    <span class="input-group-text" onclick="showPassword(password)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <span class="error" role="alert">
                @error('password')
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
    </div></div>
    
    <div class="row" style="margin-top:25px">
    <div class="col-md-4">
        <div class="form-group">  
  
            <label for="password" class="control-label">New Password</label>
            <div class="input-group">
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="">
                <div class="input-group-append">
                    <span class="input-group-text" onclick="showPassword(new_password)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <span class="error" role="alert">
                @error('new_password')
                    {{ $message }}</br>
                        @enderror
            </span>
      
        </div></div>
        <div class="col-md-4">
        <div class="form-group">
       
            <label for="password" class="control-label">Confirm Password</label>
            <div class="input-group">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="">
                <div class="input-group-append">
                    <span class="input-group-text" onclick="showPassword(confirm_password)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <span class="error" role="alert">
                @error('confirm_password')
                    {{ $message }}</br>
                        @enderror
            </span>
        </div></div></div>

    
    <script type="application/javascript">

        function showPassword(id)
            {  
                if ($(id).attr("type") == "password") {
                    $(id).attr("type", "text");
                }
                else {
                    $(id).attr("type", "password");
                }
            }


    </script>
