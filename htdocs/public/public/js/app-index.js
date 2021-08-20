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

               let $project_tracking = $('.project-tracking');

               if(type === 'checkin') {
                   $('.project-item').addClass('disabled-project');
                   $project_item.removeClass('disabled-project').addClass('active-project');
                   $target.attr('data-type', 'checkout');
               } else {
                   $project_item.removeClass('active-project');
                   $target.attr('data-type', 'checkin');

                   $project_tracking.removeClass('active-tracking');
                   $project_tracking.find('.project-tracking-current-time').html('');
                   $project_tracking.find('.project-tracking-time').html('');
                   $project_tracking.find('.project-tracking-project-name').html('none');
                   $project_tracking.removeAttr('data-project-id');

                   clearInterval(interval);
               }

               if(response.data) {

                   let data = response.data;

                   $('.project-item[data-project-id="' + data['project_id'] + '"]').find('.project-item-stats-time').text(data['total_time']);

                   if(type === 'checkin') {
                       $project_tracking.addClass('active-tracking');
                       $project_tracking.attr('data-project-id', data['project_id']);
                       $project_tracking.find('.project-tracking-current-time').html('00:00:00');
                       $project_tracking.find('.project-tracking-time').html(data['total_time']);
                       $project_tracking.find('.project-tracking-project-name').html(data['project_name']);


                       interval = setInterval(() => {

                           let $current_time = $('.project-tracking-current-time');

                           let time = $current_time.text().split(':');

                           let hours = time[0];
                           let minutes = time[1];
                           let seconds = time[2];

                           seconds++;

                           if(seconds === 60) {
                               minutes++;
                               seconds = 0;
                           }

                           if(minutes === 60) {
                               hours++;
                               minutes = 0;
                           }

                           $current_time.text(hours.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + minutes.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + seconds.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));


                            let $total_time = $('.project-tracking-time');

                            time = $total_time.text().split(':');

                            hours = time[0];
                            minutes = time[1];
                            seconds = time[2];

                           seconds++;

                           if(seconds === 60) {
                               minutes++;
                               seconds = 0;
                           }

                           if(minutes === 60) {
                               hours++;
                               minutes = 0;
                           }

                           $total_time.text(hours.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + minutes.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + seconds.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                           $('.project-item.active-project').find('.project-item-stats-time').text(hours.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + minutes.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}) + ':' + seconds.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                       }, 1000)
                   }
               }

           } else {
               alert('Error - Please check the console');
               console.log(response)
           }
       })
   })

   $('.project-tracking-checkout').on('click', (event) => {

       event.preventDefault();

       let $target = $(event.target);

       let $project_tracking = $('.project-tracking');
       let project_id = $project_tracking.attr('data-project-id');

       $.ajax({
           url: Routing.generate('api_v1_project_tracking', {projectId: project_id, type: 'checkout'}),
           method: 'GET',
           responseType: 'json'
       }).then((response) => {
           if(!response.error) {

               $('.project-item').removeClass('disabled-project').removeClass('active-project');

               $target.attr('data-type', 'checkin');

               $project_tracking.removeClass('active-tracking');
               $project_tracking.find('.project-tracking-current-time').html('');
               $project_tracking.find('.project-tracking-time').html('');
               $project_tracking.find('.project-tracking-project-name').html('none');
               $project_tracking.removeAttr('data-project-id');

               clearInterval(interval);

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