jQuery(document).ready(function(){ 
	jQuery("table.cby_persons").tablesorter();
	
	var commonsettings = {
		event: 'dblclick',
		tooltip: 'doubleclick to edit',
		placeholder:  'doubleclick to edit',
		submitdata: {
			action: 'cby_person_save'
		}
	};
	
	var ssnsettings = jQuery.extend({
		type: 'masked',
		mask: '999999999999',
		cancel: 'Cancel',
		submit: 'Save'
	}, commonsettings);
	
	var dtsettings = jQuery.extend({
		type: 'masked',
		mask: '9999-99-99 99:99'
	}, commonsettings);
	
	var tasettings = jQuery.extend({	
		type: 'autogrow',
		submit: 'Save',
		cancel: 'Cancel',
		onblur: 'ignore',
		autogrow: {
			lineHeight: 16,
			minHeight: 32
		}
	}, commonsettings);
	
	var optionsettings = jQuery.extend({
		type: 'select'
	}, commonsettings);
	
	var receivedsettings = jQuery.extend({
		data: cbydata.editoption['ReceivedGuardianOption'] // TODO Somewhat ugly :/
	}, optionsettings);
	
	jQuery('.personfield.editable.type_text').editable('/wp-admin/admin-ajax.php', commonsettings);
	jQuery('.personfield.editable.type_email').editable('/wp-admin/admin-ajax.php', commonsettings);
	jQuery('.personfield.editable.type_ssn').editable('/wp-admin/admin-ajax.php', ssnsettings);
	jQuery('.personfield.editable.type_textarea').editable('/wp-admin/admin-ajax.php', tasettings);
	jQuery('.personfield.editable.type_DateTime').editable('/wp-admin/admin-ajax.php', dtsettings);
	jQuery('.personfield.editable.type_ReceivedOption').editable('/wp-admin/admin-ajax.php', receivedsettings);
	jQuery('.personfield.editable.TicketOption').editable('/wp-admin/admin-ajax.php', jQuery.extend({data: cbydata.editoption['TicketOption']}, optionsettings));
	jQuery('.personfield.editable.BedOption').editable('/wp-admin/admin-ajax.php', jQuery.extend({data: cbydata.editoption['BedOption']}, optionsettings));
	jQuery('.personfield.editable.ShirtOption').editable('/wp-admin/admin-ajax.php', jQuery.extend({data: cbydata.editoption['ShirtOption']}, optionsettings));
	jQuery('.personfield.editable.ConsumerOption').editable('/wp-admin/admin-ajax.php', jQuery.extend({data: cbydata.editoption['ConsumerOption']}, optionsettings));
	jQuery('.personfield.editable.OilOption').editable('/wp-admin/admin-ajax.php', jQuery.extend({data: cbydata.editoption['OilOption']}, optionsettings));
});