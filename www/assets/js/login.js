$(document).ready(function(){
	var messageShow = function(msg,type){
		var classArray = ["hidden","msg_success","msg_waiting","msg_error"];
		$("#form_message").removeClass();
		$("#form_message").addClass(classArray[type]);
		$("#form_message").html(msg);
	};
	var switchTab = function(tab){
		$("#login_tab").removeClass("form_container_tab_a");
		$("#create_tab").removeClass("form_container_tab_a");
		if (tab == "create"){
			$("#login_form").hide();
			$("#create_form").show();
			$("#login_tab").addClass("form_container_tab_a");
		} else {
			$("#login_form").show();
			$("#create_form").hide();
			$("#create_tab").addClass("form_container_tab_a");
		}
	};
	
	if (typeof localStorage.getItem("lastLoggedInEmail") != "undefined"){
		$("#login_email").val(localStorage.getItem("lastLoggedInEmail"));
	}
	
	$("#login_tab").click(function(){switchTab("login");});
	$("#create_tab").click(function(){switchTab("create");});
	$("#form_message").click(function(){
		$(this).removeClass();
		$(this).addClass("hidden");
		$(this).html("No message");
	});
	
	$("#login_form").on("submit",function(event){
		event.preventDefault();
		var dontSend = false;
		
		// Verify data
		if ($("#login_pass").val().length < 6){
			messageShow("Invalid Password",3);
			dontSend = true;
		}
		
		// Send data
		if (dontSend == false){
			$.ajax({
				url:'assets/php/scripts/login.php',
				type:'POST',
				dataType:'html',
				data:$("form#login_form").serialize(),
				beforeSend: function(){
					messageShow("Logging in...",2);
					$("#submit_login").attr("disabled",true);
				},
				success: function(data){
					if (data == "success"){
						localStorage.setItem("lastLoggedInEmail",$("#login_email").val());
						messageShow("Logged in",1);
						window.location.href = "index";
					} else {
						messageShow(data,3);
					}
					$("#submit_login").attr("disabled",false);
				},
				error: function(e){
					messageShow("Connection Error",3);
					$("#submit_login").attr("disabled",false);
					console.log(e);
				}
			});
		}
	});
	
	$("#create_form").on("submit",function(event){
		event.preventDefault();
		var dontSend = false;
		
		// Verify data
		if ($("#create_pass").val() != $("#create_passconf").val()){
			messageShow("Passwords don't match",3);
			dontSend = true;
		}
		if ($("#create_pass").val().length < 6){
			messageShow("Password too short",3);
			dontSend = true;
		}
		
		// Send data
		if (dontSend == false){
			$.ajax({
				url:'assets/php/scripts/create.php',
				type:'POST',
				dataType:'html',
				data:$("form#create_form").serialize(),
				beforeSend: function(){
					messageShow("Creating Account...",2);
					$("#submit_create").attr("disabled",true);
				},
				success: function(data){
					if (data == "success"){
						messageShow("Account created!",1);
						switchTab("login");
						$("#login_email").val($("#create_email").val());
					} else {
						messageShow(data,3);
					}
					$("#submit_create").attr("disabled",false);
				},
				error: function(e){
					messageShow("Connection Error",3);
					$("#submit_create").attr("disabled",false);
					console.log(e);
				}
			});
		}
	});
});