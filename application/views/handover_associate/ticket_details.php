<?= $this->load->view('top', '', TRUE) ?>
<div class="container py-5 mb5">
  <h3 class="mb-3">TICKET DETAILS</h3>
  	 <?php if($ticket_bind) :
    	$customer_number = "";
    	$unit_number = "";
    	$parking = "";
    	$customer_name = "";
    	$unit_type = "";
    	$ticket_type = "";
    	foreach($ticket_bind as $bind):
    		$customer_number .= $bind->customer_number . ' ,';
    		$unit_number .= $bind->unit_number . $bind->unit_desc . ' ,';
    		$parking .= $bind->parking_number . ' ,';
    		$customer_name .= $bind->customer_name . ' ,';
    		$unit_type .= $bind->unit_type . ' ,';
    	endforeach;

    	if($unit_number && !$parking) {
    		// UNIT ONLY
    		$ticket_type = 'U';
    	} else if (!$unit_number && $parking) {
    		// PARKING ONLY
    		$ticket_type = 'P';
    	} else if($unit_number && $parking) {
    		// UNIT AND PRKING
    		$ticket_type = 'UP';
    	}
    endif; ?>
  	<div class="row">
		<div class= "col-md-12">
	  		<table class="table" id="tickets_table">
			  	<thead class="thead-light">
				    <tr>
				      <th scope="col">Ticket No.</th>
				      <th scope="col">Customer Number</th>
				      <th scope="col">Created By</th>
				      <th scope="col">Date Created</th>
				      <th scope="col">Last Update</th>
				      <th scope="col">TAT (DD:MM:SS)</th>
				    </tr>
			  	</thead>
			  	<tbody>
			  		
				    <tr>
				      <th scope="row"><?= $ticket_details->ticket_number ?></th>
				      <th scope="row"><?= $ticket_details->customer_number ?></th>
				      <th scope="row"></th>
				      <th scope="row"><?= $ticket_details->date_created; ?></th>
					  <th scope="row"><?= $ticket_details->last_update; ?></th>
				      <th scope="row"><?php
						$date_expire = $ticket_details->date_created;    
						$date = new DateTime($date_expire);
						$now = new DateTime();
						echo $date->diff($now)->format("%d days, %h hours and %i minutes"); ?></th>
				  	</tr>
				 
				</tbody>
			</table>
	  	</div>

  		<div class= "col-md-9">
	  	
		  	<div class= "col-md-12">
		  		<div class="card border-primary mb-3">
		  			<div class="card-header text-primary" style="background-color: #343758; color: #e9ecef !important;">
		  				<h5>SUMMARY</h5>
		  			</div>
				  <div class="card-body text-primary">
				  	<?php $schedule = $this->Admin_model->get_schedules_per_customer_number($ticket_details->customer_number); ?>
				  	<?php if($schedule): ?>
				    <p class="card-text">
				    	<div class="">
				    		<b>Date Qualified for Turnover: </b> December 1, 2019 9:00 AM 
				    	</div>
				    	<div class="">
				    		<b>Turnover Schedule: </b> <?= $schedule->schedule ?>
				    	</div>
				    </p>
					<?php endif; ?>
				      <a href="<?= base_url('handover/turnover_process/'.$ticket_details->ticket_id.'/'.$ticket_type); ?>" class="btn btn-dark">Turnover Process</a>

					<button type="button" class="btn btn-outline-dark">No Show</button>
				  </div>
				</div>
		  	</div>

		  	<div class= "col-md-12">
		  		<div class="card border-primary mb-3">
		  			<div class="card-header text-primary" style="background-color: #343758; color: #e9ecef !important;">
		  				<h5>ACTIVITIES</h5>
		  			</div>
				  	<div class="card-body text-primary">
				  		<div class="row" style="border-bottom: 1px #808080 solid">
						    <div class="col-md-4 border-primary">
						    	<span>Unit Owner | December 1, 2019 9:00 AM </span>
						    </div>
						    <div class="col-md-8 border-primary">
						    	Turnover schedule has been successfully booked by Unit Owner.
						    </div>
						</div>
				  </div>
				</div>
		  	</div>

		  	<div class= "col-md-12">
		  		<div class="card border-primary mb-3">
		  			<div class="card-header text-primary" style="background-color: #343758; color: #e9ecef !important;">
		  				<h5>ADD NOTE</h5>
		  			</div>
				  	<div class="card-body text-primary">
				  		<form action="<?= base_url('handover/add_ticket_note') ?>" role="form" method="POST" enctype="multipart/form-data">
			  			<input type="hidden" class="form-control" id="ticket_id" name="ticket_number" placeholder="" value="<?= $this->uri->segment(3) ?>">
			  			<input type="hidden" class="form-control" id="ticket_number" name="ticket_number" placeholder="" value="<?= $ticket_details->ticket_number ?>">
				  		<div class="row">
						    <div class="col-md-12">
						    	<div class="form-group">
						    		<label for="status">VIEW STATUS</label>
						    		<div class="custom-control custom-checkbox">
							          <input type="checkbox" class="custom-control-input" id="status" name="status" value="1">
							          <label class="custom-control-label" for="status">PRIVATE</label>
							        </div>
						    	</div>
						    </div>
						    <div class="col-md-12">
						    	<div class="form-group">
						    		<?php $users = $this->Admin_model->get_users(); ?>
						    		<?php if($users): ?>
							    		<label for="team_huddle">TEAM HUDDLE</label>
							            <select class="custom-select d-block w-100" id="team_huddle" name="team_huddle">
							            	<option value=""> -- Please Choose -- </option>
							            	<?php foreach($users as $user): ?>
							              	<option value="<?= $user->user_id ?>"> <?= $user->lastname ?>, <?= $user->firstname ?> </option>
							              	<?php endforeach; ?>
							            </select>
							        <?php endif; ?>
						    	</div>
						    </div>

						    <div class="col-md-12">
						    	<div class="form-group">
						    		 <label for="note"> NOTE</label>
						            <textarea class="form-control" rows="5" id="note" name="note"></textarea>
						    	</div>
						    </div>

						    <div class="col-md-12">
						    	<div class="form-group">
						    		<label for="file_upload">UPLOAD</label>
						            <input type="file" accept="image/*" id="capture" name="capture_img" capture="camera">
						    	</div>
						    </div>
						</div>
						<br>
						<button type="submit" class="btn btn-dark">Add Note</button>
					</form>
				  </div>

				</div>
			</div>
	  	</div>
	  	<div class="col-md-3">
	  		<div class="card text-white bg-primary mb-3">
			  <div class="card-header">DETAILS</div>
			  <div class="card-body">
		  		<div class="row">
			            <label for="project">Project</label>
			            <input type="text" class="form-control" id="project" name="project" placeholder="" value="<?= $ticket_details->project_name ?>">

			            <label for="tower">Tower</label>
			            <input type="text" class="form-control" id="tower" name="tower" placeholder="" value="<?= $ticket_details->tower ?>">

			            <label for="unit_number">Unit Number</label>
			            <input type="text" class="form-control" id="unit_number" name="unit_number" placeholder="" value="<?= $unit_number ?>">

			            <label for="unit">Unit</label>
			            <input type="text" class="form-control" id="unit" name="unit" placeholder="" value="<?= $unit_type ?>">

			            <label for="parking">Parking Number</label>
			            <input type="text" class="form-control" id="parking" name="parking" placeholder="" value="<?= $parking ?>">

			            <label for="customer_name">Customer Name</label>
			            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="" value="<?= $customer_name ?>">

			            <label for="ticket_type">Ticket Type</label>
			            <input type="text" class="form-control" id="ticket_type" name="ticket_type" placeholder="" value="<?= $ticket_details->subject ?>">

			            <label for="assigned_to">Assigned To</label>
			            <input type="text" class="form-control" id="assigned_to" name="assigned_to" placeholder="" value="<?= $ticket_details->a_lastname ?>, <?= $ticket_details->a_firstname ?>">

			            <label for="status">Status</label>
			            <input type="text" class="form-control" id="status" name="status" placeholder="" value="Call Confirmation">

		    	</div>  
			  </div>
			</div>
	  	</div>




  	</div>
  </div>