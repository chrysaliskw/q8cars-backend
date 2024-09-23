<x-admin-layout title="Online News">
   <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Online News</li>
    </x-slot>
        <?php 
            $page = $_GET['page'] ?? 1;
        ?>
        
    <x-crud-index-for-feed title="" 
        selectUrl=""
        page="{{ $page }}"
        createUrl="{{ route('admin.news.feed.publish-all', ['page' => $page]) }}" 
        createButtonText="Publish/Unpublish News">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index-for-feed>
    <x-slot name="scripts">

        {!! $grid->scripts() !!}

        <script type="application/javascript">
          
           const selectBox = $("#selectBox");

           function publishNews(identifier)
           {
              swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes',
                  input: 'checkbox',
                  inputPlaceholder: 'Allow Notification?',  
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.dismiss) 
                  {
                  return;
                }else{
                     
                      
                      const url = "{{ route('admin.news.feed.publish') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id + '&send_push=' + result.value;
                }
              });
           }

           function UnpublishNews(identifier){
               
            swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes', 
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.value) { 
                      const url = "{{ route('admin.news.feed.publish') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id;
                }
              });
           }

           function markForPublish(identifier)
           {
                var newsId = $(identifier).data().id;
                var checkValue = 1;

                if ($(identifier).prop("checked") == true) {
                    checkValue = 1;
                }
                else if($(identifier).prop("checked") == false) {
                    checkValue = 2;
                }
             

                $.ajax({
                    type:'get',
                    url:"{{ route('admin.news.feed.mark') }}",
                    data:{
                        'id':newsId,
                        'is_publish': checkValue,
                    },
                    success: function (data) {
                        console.log(data);
                    },  
                    error: function (data) {
                        console.log(data);
                    }  
                });
           }

           function selectAll(that) 
           {
                var page = $(that).data().page;
                var checkValue = 1;

                if ($(that).prop("checked") == true) {
                    checkValue = 1;
                }
                else if($(that).prop("checked") == false) {
                    checkValue = 2;
                }

              //  const url = "{{ route('admin.news.feed.select-all') }}";
              //  window.location.href = url + '?page=' + $(identifier).data().page;

                $.ajax({
                    type:'get',
                    url:"{{ route('admin.news.feed.select-all') }}",
                    data:{
                        'page':page,
                        'check': checkValue,
                    },
                    beforeSend: function() {
                       
                        selectBox.prepend(getSpinnerHtml());
                    },

                    success: function (data) {
                        console.log(data.length);
                        console.log(data);
                        $("#chat-spinner").remove();
                      
                        for(i = 0; i < data.length; i++) 
                        {
                            var s = data[i];
                           
                            if ($(that).prop("checked") == true) {
                                $('#mark_'+s).prop("checked", true);
                               
                            }

                            if ($(that).prop("checked") == false) {
                                $('#mark_'+s).prop("checked", false);
                              
                            }
                
                        }
                    },  
                    error: function (data) {
                        console.log(data);
                    
                    }  
                });
           }

           function getSpinnerHtml()
            {
                const html = '<div id="chat-spinner" class="text-center" style="font-size: 18px;">' +
                            '<i class="fa fa-spin fa-refresh"></i>' +
                        '</div>';

                return html;
            }

            function unsetBreaking(identifier)
            {
              swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.dismiss) 
                  {
                  return;
                }else{
                    
                      
                      const url = "{{ route('admin.news.feed.breaking') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id;
                }
              });
           }

           function setBreaking(identifier){
               
            swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes', 
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.value) { 
                      const url = "{{ route('admin.news.feed.breaking') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id;
                }
              });
           }



        </script>

    </x-slot>
</x-admin-layout>
