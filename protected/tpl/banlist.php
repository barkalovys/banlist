<div class="form-group">
  <label class="control-label" for="quickfind">Быстрый поиск</label>
  <input class="form-control" id="quickfind" type="text">
</div>
<table class="table table-striped table-hover ">
                <thead>
                  <tr class="danger">
                    <th>IP</th>
                    <th>Причина блокировки</th>
                    <th>Дата блокировки</th>
                    <th>Дата разблокировки</th>
                    <th class="empty"></th>
                    <th class="empty"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $list = $this->getAll();
                    foreach ($list as $item) :
                  ?>
                  <tr id="<?php echo $item['id'] ?>">
                    <td class="editable ip"><?php echo $item['ip']?></td>
                    <td class="editable reason"><?php echo $item['reason']?></td>
                    <td class="editable block_date">
                        <?php 
                              $block_date =  new DateTime($item['block_date']); 
                              echo $block_date->format('d.m.Y H:i:s');
                         ?>
                    </td>
                    <td class="editable unblock_date">
                        <?php 
                              if ($item['unblock_date']) {
                                $unblock_date =  new DateTime($item['unblock_date']);
                                echo (!$unblock_date->getTimestamp())?'':$unblock_date->format('d.m.Y H:i:s');
                              }
                         ?>
                    </td>
                    <td  class="block <?php echo $item['is_blocked']?'locked':'unlocked'?>"></td>
                    <td class="delete"></td>
                  </tr>
                  <?php endforeach;?>

                </tbody>
              </table>
             
             
              <button id="add" class="btn btn-default">Добавить запись</button>
              <span id="error-msg"></span>
                          
<script type="text/javascript">

$(document).ready(function() 
	    { 
    
		var options = {
			additionalFilterTriggers: [$('#quickfind')]
		};
		$('.table').tableFilter(options);
	        $(".table").tablesorter(); 
	        var edit = false;
	       	var content = false;
	       	var err = $('#error-msg');

	       	//двойной клик по записи для редактирования
	       	$(document).on('dblclick', 'td.editable', function(e){
	        	if (!edit) {
		        	edit = this;

		        	//сохраняем содержимое ячейки таблицы в глобальную переменную
		        	content =  $(this).text();
		        	
		        	$(this).html("<input type='text' value='"+content.trim()+"'/>" +
		        				 "<button type='button' id='apply-changes' class='btn btn-primary btn-custom'>ok</button>" +
		        				 "<button type='button' id='cancel-changes' class='btn btn-default btn-custom'>отмена</button>");
		        	
	        	}
	        });

	       	
	        //отмена всех изменений
	        $(document).on('click', '#cancel-changes', function(){
		    	$(edit).html(content); 
		    	edit = false;
		    	content = false;
		    	err.html('').hide();
	        });

		    //сохранение изменений
	        $(document).on('click', '#apply-changes', function(){
	        	
	        	var td = $(this).parent('td');
	            var tr = td.parent('tr');
	        	var id = tr.attr('id');
	        	
	            //какое поле редактируем:
	            var field = td.attr('class').trim().split(" ")[1];
	            var field_value = $(edit).children('input').val();
	            var is_blocked = ($(tr).children('td.locked').length)?1:0;
	           
	            var block_date = $(tr).children('.block_date').html().trim();;
	            var unblock_date = $(tr).children('.unblock_date').html().trim();;

	            if (field === 'block_date') 
	                block_date = field_value;
	            else if (field === 'unblock_date')
		            unblock_date = field_value;
	            
	            
		        $.ajax({
		        	  url: "/?banlist/update/"+field,
		        	  type: "POST",
		        	  dataType: "json",
		        	  data: {id: id, field_value: field_value, 
		        		  block_date: block_date,
		        		  unblock_date:unblock_date,
		        		  is_blocked:is_blocked},
		        	  success: function(res){
		        		if (!res.error) { 
		        			content = field_value; 
			  	        	$(edit).html(content);
			  	        	if (res.block_date)
				  	        	 $(tr).children('.block_date').html(res.block_date);
			  	        	else if (res.unblock_date)
			  	        	     $(tr).children('.unblock_date').html(res.unblock_date);
		  	        	    if (res.is_blocked == 1)
			  	        	    $(tr).children('.block').attr('class', 'block locked');
		  	        	    else
		  	        	    	$(tr).children('.block').attr('class', 'block unlocked');
					    	edit = false;
					    	content = false;
					    	err.html('').hide();
		        		}
		        		else {
		        		    err.html(res.error);
		        		    err.show();
		        		};
				      },
				      error: function (xhr, ajaxOptions, thrownError) {
       		    	   console.log(xhr);
       		    	   console.log(ajaxOptions);
       		    	   console.log(thrownError);
	        		 }
				});
	        });			
	        $(document).on(
	    	 'focus', 'td.block_date input, td.unblock_date input,input[name=block_date], input[name=unblock_date]', function(){
		        
	        	$(this).datetimepicker({
	        	    timeFormat: "HH:mm:ss",
	        	    dateFormat: "dd.mm.yy"
		        });
		    });	  
			//удалить запись из бд
	        $(document).on('click', '.delete', function(){
	        	if (confirm('Вы уверены, что хотите удалить пользователя из БД?')) {
	        	    var tr = $(this).parent('tr');
	        	    var id = tr.attr('id');
	        		$(this).parent('tr').fadeOut();
	        		$.ajax({
	        			  url: "/?banlist/delete/"+id,
	        			  type: "POST",
	        			});
	        	}	
	        });

	        //зеленый замок - пользователь заблокирован,
	        //красный - разблокирован
	        $(document).on('click', '.block', function(){
	        	var is_blocked;
	        	var tr = $(this).parent('tr');
	        	var td = $(this);
	        	var id = tr.attr('id');
	            is_blocked = ($(this).hasClass('locked'))?0:1;
        		$.ajax({
        			  url: "/?banlist/update/is_blocked",
        			  type: "POST",
        			  data: {field_value: is_blocked, id: id},
        			  dataType: "json",
        			  success: function(res){
        				 
        				  console.log(res.date);
      				  if (is_blocked){
        					td.attr('class', 'block locked');
        					tr.children('.block_date').html(res.date);
        					tr.children('.unblock_date').html('');
      				  }		
      				  else {
        					td.attr('class', 'block unlocked');
        					tr.children('.unblock_date').html(res.date);
      				  }
    					
                	  }
        			});
    	
	        });
	        

	        //При клике на кнопку добавляем новый ряд инпутов в таблицу
	        //Меняем кнопку "добавить" на кнопку "подтвердить"
	        $(document).on('click', '#add', function(){
	            $(this).attr('id', 'confirm').html('Подтвердить добавление');
	            $('.table tbody').append(
	    	              '<tr id=\'new\'><td><input type="text" name=\'ip\'/></td>'+
	            		  '<td><input type="text" name=\'reason\'/></td>'+
	            		  '<td><input type="text" name=\'block_date\'/></td>'+
	            		  '<td><input type="text" name=\'unblock_date\'/></td>'+
	            		  '<td></td>'+
	            		  '<td class="cancel"></td></tr>');	
		    });

	        //Подтверждение добавления записи в бд
	        $(document).on('click', '#confirm', function(){
	            var ip = $("#new input[name=ip]").val();
	            var reason = $("#new input[name=reason]").val();
	            var block_date = $("#new input[name=block_date]").val();
	            var unblock_date = $("#new input[name=unblock_date]").val();
	            
        		$.ajax({
        			  url: "/?banlist/add",
        			  type: "POST",
        			  data: {ip:ip, reason:reason, block_date:block_date, unblock_date:unblock_date},
        			  dataType: "json",
       			      success: function(res){
           			      //Если всё нормально - убираем инпуты 
           			      //и записываем добавленные данные в конец таблицы:
          			       if (!res.error) {
              			      var block = (res.is_blocked)?'locked':'unlocked';
           			    	  console.log(res);
              			       $('#new').remove();
             		           $('.table tbody').append(
                	    	              '<tr id=\''+res.id+'\'><td class=\'editable ip\'>'+res.ip+'</td>'+
                	            		  '<td class=\'editable reason\'>'+res.reason+'</td>'+
                	            		  '<td class=\'editable block_date\'>'+res.block_date+'</td>'+
                	            		  '<td class=\'editable unblock_date\'>'+res.unblock_date+'</td>'+
                	            		  '<td class=\'block '+block+'\'></td>'+
                	            		  '<td class="delete"></td></tr>');
              		          //Меняем кнопку обратно:
              		          $('#confirm').attr('id', 'add').html('Добавить запись');
                		      err.html('').hide();
          			       }
      		        		else {
    		        		    err.html(res.error);
    		        		    err.show();
    		        		}          			       
        			  },
        		      error: 
            		    function (xhr, ajaxOptions, thrownError) {
           		    	   console.log(xhr);
           		    	   console.log(ajaxOptions);
           		    	   console.log(thrownError);
        		        }
        			});
		    });
			//отменить добавление записи
	        $(document).on('click', '.cancel', function(){
	            var tr = $(this).parent('tr');
	        	tr.fadeOut().remove();
	        	$('#confirm').attr('id', 'add').html('Добавить запись');
	        });	

  
	    } 
	); 
</script>
              
