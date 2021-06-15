$(document).ready(function()
{	
	$(function(){
		$("#message").delay(5000).slideUp(300);
	});

	$(".Name_structure").change(function()
	//document.getElementById("Name_structure").onchange = function()
		{
			alert("vgvgvg");
			var OptionIndex = document.getElementById("Name_structure").options.selectedIndex;
			var OptionValue= document.getElementById("Name_structure").options[OptionIndex].value;
			alert(OptionValue);
		});

	$(function(){
		$("#Phone_Number").mask("+7(999) 999-99-99"); //99-99-99
	});

	$(function(){
		$("#email").inputmask("email");
	});

	$(".Name_Edt").change(function()
//document.getElementById("addEdt").onchange = function()
	{
		document.getElementById('divAdd').hidden = true;
		document.getElementById('part').hidden = false;
		document.getElementById('partEdt').hidden = false;
	 //console.log($(this).val());
		OptionValue = $(this).val();   	

		$.ajax({   
			type: "POST",   
			url: "V_Edt.php",  
			data:  "OptionValue="+OptionValue,
			dataType: "html",
			async: false, 
			success: function(data)
			{ 
				//alert(data);
				console.log(data);
				 $('#res').html(data); 
			}   
		});	
		
	});
	//document.getElementById("prov").onclick = function()
	$('.prov').click(function()
	{ 	
		if ($(".LastName").val() == "") {
			$('.LastName').addClass('empty_field');	 
			alert();
		}
		LastName=document.getElementById("LastName").value;
		FirstName=document.getElementById("FirstName").value;
		Patronymic=document.getElementById("Patronymic").value;
		if (LastName == '') {
			$('.LastName').addClass('empty_field');	 
		}
		else{
			$('.LastName').removeClass('empty_field');
		}
	

		$.ajax({   
			type: "POST",   
			url: "OfficeDep.php", 
			data:  "LastName="+LastName+"&FirstName="+FirstName+"&Patronymic="+Patronymic,
			dataType: "html",
			async: false, 
			success: function(data)
			{ 
				//alert(data);
				//console.log(data);
				 $('#off').html(data); 
			}   
		});	
		document.getElementById("Country").disabled = false;
		document.getElementById("email").disabled = false;
		document.getElementById("Phone_Number").disabled = false;
		document.getElementById("login_user").disabled = false;
		document.getElementById("password_user").disabled = false;
		document.getElementById("check_password").disabled = false;
	
	
	});


	document.getElementById("EDN_type_ed").onchange = function()
		{
			EDN_type_ed=document.getElementById('EDN_type_ed').value;
			switch (EDN_type_ed)
			{
				case null: break;
				case 'Series':
					document.getElementById('bl_ISBN').hidden = true;
					document.getElementById('bl_ISSN').hidden = false;
					document.getElementById('bl_ISSN_O').hidden = false;
					break;
				case 'Book':
					document.getElementById('bl_ISSN').hidden = true;
					document.getElementById('bl_ISSN_O').hidden = true;
					document.getElementById('bl_ISBN').hidden = false;
					break;
			}
		};
			
		$(function () {
            $('#Name_PDO').on('input', function () {
                var opt = $('option[value="' + $(this).val() + '"]');
				if (opt.length )
				{
					opt.attr('id');
					//alert($(this).val());
					document.getElementById('Publ').hidden = true;
					document.getElementById('Publ_t').hidden = true;
				}
				else{
					//alert('NO OPTION');
					document.getElementById('Publ').hidden = false;
					document.getElementById('Publ_t').hidden = false;
				}
			
            });
        });
	 

		
	$('.partEdt').click(function()
	{
		id_Edt=document.getElementById('id_Edt').value;
		Name_part=document.getElementById('Name_part').value;
		Num_Article=document.getElementById('Num_Article').value;
		PageBg=document.getElementById('PageBg').value;
		PageEnd=document.getElementById('PageEnd').value;
		PageCount=document.getElementById('PageCount').value;
		URL_Art=document.getElementById('URL_Art').value;
		id_TypePart=document.getElementById('id_TypePart').value;
		Part_Namelang=document.getElementById('Part_Namelang').value;
		alert("ну пожалуйста!!!!"+id_Edt);	

		$.ajax({   
			type: "POST",   
			url: "part_Edt.php",  
			data:  "id_Edt="+id_Edt+"&Name_part="+Name_part+"&Num_Article="+Num_Article+"&PageBg="+PageBg+"&PageEnd="
			+PageEnd+"&PageCount="+PageCount+"&URL_Art="+URL_Art+"&id_TypePart="+id_TypePart+"&Part_Namelang="+Part_Namelang,
			dataType: "html",
			async: false, 
			success: function(data)
			{ 
				//alert(data);
				console.log(data);
				 $('#result').html(data); 
			}   
		});	
	});

	var $selectbox = $('.Name_Edt');
	$('.addEdt').click(function()
	{
		$selectbox.prop('selectedIndex', 0);  
		if (document.getElementById('div-2')==null){
			document.getElementById('divAdd').hidden = false;
			document.getElementById('add').hidden = false;
			document.getElementById('part').hidden = false;
			document.getElementById('partEdt').hidden = true;
		}
		else{
			document.getElementById('div-2').hidden = true;//скрывается
			document.getElementById('divAdd').hidden = false;// показывает
			document.getElementById('add').hidden = false;
			document.getElementById('part').hidden = false;
			document.getElementById('partEdt').hidden = true;
		}
	});

	document.getElementById('divAdd').hidden = true;
	document.getElementById('bl_ISSN').hidden = true;
	document.getElementById('bl_ISSN_O').hidden = true;
	document.getElementById('bl_ISBN').hidden = true;
	document.getElementById('Publ').hidden = true;
	document.getElementById('Publ_t').hidden = true;
	document.getElementById('part').hidden = true;
	document.getElementById('add').hidden = true;
	document.getElementById('partEdt').hidden = true;
});