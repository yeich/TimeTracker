$(() => {
   $('.project-item-checkinout').on('click', (event) => {

       event.preventDefault();

       let $target = $(event.target);

       let type = $target.attr('data-type');
       let $project_item = $target.closest('.project-item');

       $.ajax({
           url: Routing.generate('api_v1_project_tracking', {projectId: $project_item.attr('data-project-id'), type: type}),
           method: 'GET',
           responseType: 'json'
       }).then((response) => {
           if(!response.error) {

               $('.project-item').removeClass('disabled-project');

               if(type === 'checkin') {
                   $('.project-item').addClass('disabled-project');
                   $project_item.removeClass('disabled-project').addClass('active-project');
                   $target.attr('data-type', 'checkout');
               } else {
                   $project_item.removeClass('active-project');
                   $target.attr('data-type', 'checkin');
               }

               if(response.data) {

                   let data = response.data;

                   $('.project-item[data-project-id="' + data['project_id'] + '"]').find('.project-item-stats-time').text(data['total_time']);
               }

           } else {
               alert('Error - Please check the console');
               console.log(response)
           }
       })
   })
});