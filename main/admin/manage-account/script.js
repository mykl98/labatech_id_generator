$(document).ready(function() {
    setTimeout(function(){
        $("#manage-account-menu").attr("href","#");
        $("#manage-account-menu").addClass("active");
    },100)
})

$(document).on('shown.lte.pushmenu', function(){
    $("#global-department-name").show();
    $("#global-client-logo").attr("width","100px");
})

$(document).on('collapsed.lte.pushmenu', function(){
    $("#global-department-name").hide();
    $("#global-client-logo").attr("width","40px");
})

$(".modal").on("hidden.bs.modal",function(){
    $(this).find("form").trigger("reset");
})

getAccountList();
getUserDetails();

accountIdx = "";

function getUserDetails(){
    $.ajax({
        type: "POST",
        url: "get-profile-settings.php",
        dataType: 'html',
        data: {
            dummy:"dummy"
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                renderUserDetails(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}

function renderUserDetails(data){
    var lists = JSON.parse(data);

    lists.forEach(function(list){
        if(list.image != ""){
            $("#global-user-image").attr("src", list.image);
        }
        $("#global-user-name").text(list.name);
    })

}

function getAccountList(){
    $.ajax({
		type: "POST",
		url: "get-account-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderAccountList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderAccountList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="manage-account-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Access Card</th>\
                                <th>Name</th>\
                                <th>Username</th>\
                                <th>Access</th>\
                                <th>Status</th>\
                                <th style="max-width:50px;min-width:50px;">Action</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var status = list.status;
        if(status == "active"){
            status = '<span class="badge badge-success">Active</span>';
        }else if(status == "inactive"){
            status = '<span class="badge badge-danger">Inactive</span>';
        }
        markUp += '<tr>\
                        <td>'+list.card+'</td>\
                        <td>'+list.name+'</td>\
                        <td>'+list.username+'</td>\
                        <td>'+list.access+'</td>\
                        <td>'+status+'</td>\
                        <td>\
                            <button class="btn btn-success btn-sm" onclick="editAccount(\''+ list.idx +'\')"><i class="fa fa-pencil"></i></button>\
                            <button class="btn btn-danger btn-sm" onclick="deleteAccount(\''+ list.idx +'\')"><i class="fas fa-trash"></i></button>\
                        </td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#manage-account-table-container").html(markUp);
    $("#manage-account-table").DataTable();
}

function addAccount(){
    accountIdx = "";
    $("#add-edit-account-modal").modal("show");
    $("#add-edit-account-modal-title").text("Add New Account");
    $("#add-edit-account-modal-error").text("");
}

function saveAccount(){
    if(startCapture){
        return;
    }
    var card = $("#account-card").val();
    var name = $("#account-name").val();
    var username = $("#account-username").val();
    var access = $("#account-access").val();
    var status = $("#account-status").val();

    var error = "";
    if(card == "" || card == undefined){
        error = "*Please scan RFID card.";
    }else if(name == "" || name == undefined){
        error = "*Name field should not be empty.";
    }else if(username == "" || username == undefined){
        error = "*Username field should not be empty.";
    }else if(access == "" || access == undefined){
        error = "*Please select access level!";
    }else{
        $.ajax({
            type: "POST",
            url: "save-account.php",
            dataType: 'html',
            data: {
                idx:accountIdx,
                card:card,
                name:name,
                username:username,
                access:access,
                status:status
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    $("#add-edit-account-modal").modal("hide");
                    getAccountList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
    $("#add-edit-account-modal-error").text(error);
}

function editAccount(idx){
    accountIdx = idx;
    $.ajax({
        type: "POST",
        url: "get-account-detail.php",
        dataType: 'html',
        data: {
            idx:idx
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                renderEditAccount(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}

function renderEditAccount(data){
    var lists = JSON.parse(data);

    lists.forEach(function(list){
        $("#account-card").val(list.card);
        $("#account-name").val(list.name);
        $("#account-username").val(list.username);
        $("#account-access").val(list.access);
        $("#account-status").val(list.status);
        $("#add-edit-account-modal-title").text("Edit " + list.name + "'s Account Details");
    })
    $("#add-edit-account-modal-error").text("");
    $("#add-edit-account-modal").modal("show");
}

function deleteAccount(idx,name){
    if(confirm("Are you sure you want to delete this Account?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "delete-account.php",
            dataType: 'html',
            data: {
                idx:idx,
                name:name
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getAccountList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function logout(){
    $.ajax({
        type: "POST",
        url: "logout.php",
        dataType: 'html',
        data: {
            dummy:"dummy"
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                window.open("../../../index.php","_self")
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}

/************** RFID Reader ************/
var startCapture = false;
var cardId = "";
$(function() {
	$(window).keypress(function(e) {
		var ev = e || window.event;
		var key = ev.keyCode || ev.which;
		if((key == 48) && startCapture == false){
			cardId = "";
			startCapture = true;
			setTimeout(function(){
				startCapture = false;
			},200);
		}
		if(key == 13 && startCapture == true){
            setTimeout(function(){
                $("#account-card").val(cardId);
                startCapture = false;
			},200);
		}
        if(startCapture == true){
			cardId += String.fromCharCode(key);
		}
	});
});