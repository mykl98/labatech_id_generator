$(document).ready(function() {
    setTimeout(function(){
        $("#manage-machine-menu").attr("href","#");
        $("#manage-machine-menu").addClass("active");
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

$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal.show').length && $(document.body).addClass('modal-open');
});

getUserDetails();
filterChange();
var reportIdx;
var mapInitFlag = false;
var map;

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

function filterChange(){
    var from = $("#filter-from").val();
    var to = $("#filter-to").val();
    var account = $("#filter-account").val();
    getMachineList(from, to, account);
}

function getMachineList(from, to, account){
    $.ajax({
		type: "POST",
		url: "get-machine-list.php",
		dataType: 'html',
		data: {
			from:from,
            to:to,
            account:account
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderMachineList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderMachineList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="report-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Date</th>\
                                <th>Time</th>\
                                <th>Phone Number</th>\
                                <th>Report Type</th>\
                                <th>Status</th>\
                                <th style="max-width:50px;min-width:50px;"></th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var status = list.status;
        var type = list.type;
        var button = "";
        if(status == "001"){
            status = '<span class="badge badge-danger">Waiting</span>';
            button = '<div class="dropdown">\
                            <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                            <ul class="dropdown-menu">\
                                <li><a href="#" class="pl-2" onclick="viewReport('+list.idx+')"><i class="fas fa-eye"></i> View</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="#" class="pl-2" onclick="locateReport('+list.idx+')"><i class="fas fa-map-marker"></i> Locate</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="https://maps.google.com/?q='+list.lat+','+list.lng+'" target="_blank" class="pl-2"><i class="fas fa-cloud"></i> Google Map</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="#" class="pl-2" onclick="despatchReport('+list.idx+')"><i class="fas fa-rocket"></i> Despatch</a></li>\
                            </ul>\
                      </div>';
        }else if(status == "002"){
            status = '<span class="badge badge-success">Despatched</span>';
            button = '<div class="dropdown">\
                            <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                            <ul class="dropdown-menu">\
                                <li><a href="#" class="pl-2" onclick="viewReport('+list.idx+')"><i class="fas fa-eye"></i> View</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="#" class="pl-2" onclick="locateReport('+list.idx+')"><i class="fas fa-map-marker"></i> Locate</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="https://maps.google.com/?q='+list.lat+','+list.lng+'" target="_blank" class="pl-2"><i class="fas fa-cloud"></i> Google Map</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="#" class="pl-2" onclick="completeReport('+list.idx+')"><i class="fas fa-window-close"></i> Finish</a></li>\
                            </ul>\
                      </div>';
        }else if(status == "003"){
            status = '<span class="badge badge-dark">Completed</span>';
            button = '<div class="dropdown">\
                            <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                            <ul class="dropdown-menu">\
                                <li><a href="#" class="pl-2" onclick="viewReport('+list.idx+')"><i class="fas fa-eye"></i> View</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="#" class="pl-2" onclick="locateReport('+list.idx+')"><i class="fas fa-map-marker"></i> Locate</a></li>\
                                <div class="dropdown-divider"></div>\
                                <li><a href="https://maps.google.com/?q='+list.lat+','+list.lng+'" target="_blank" class="pl-2"><i class="fas fa-cloud"></i> Google Map</a></li>\
                            </ul>\
                      </div>';
        }
        if(type == "001"){
            type = '<span class="badge badge-warning">Request for ambulance.</span>';
        }else if(type == "002"){
            type = '<span class="badge badge-danger">Fire incident report.</span>';
        }else if(type == "003"){
            type = '<span class="badge badge-info">Request for police assistance.</span>';
        }else if(type == "004"){
            type = '<span class="badge badge-success">Request for road side repair assistance.</span>';
        }
        markUp += '<tr>\
                        <td>'+list.date+'</td>\
                        <td>'+list.time+'</td>\
                        <td>'+list.number+'</td>\
                        <td>'+type+'</td>\
                        <td>'+status+'</td>\
                        <td>'+button+'</td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#report-table-container").html(markUp);
    $("#report-table").DataTable();
}

function viewReport(idx){
    reportIdx = idx;
    $.ajax({
		type: "POST",
		url: "get-report-detail.php",
		dataType: 'html',
		data: {
			idx:reportIdx
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderViewReport(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderViewReport(data){
    var lists = JSON.parse(data);
    lists.forEach(function(list){
        $("#report-datetime").val(list.date +"  at  "+ list.time);
        $("#report-number").val(list.number);
        $("#report-lat").val(list.lat);
        $("#report-lng").val(list.lng);
        $("#report-type").val(list.type);
        $("#report-status").val(list.status);
        $("#report-detail").val(list.detail);
    })
    $("#view-report-modal").modal("show");
}

function locateReport(idx){
    reportIdx = idx;
    $.ajax({
		type: "POST",
		url: "get-report-detail.php",
		dataType: 'html',
		data: {
			idx:reportIdx
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderLocateReport(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderLocateReport(data){
    var lists = JSON.parse(data);
    var lat = "";
    var lng = "";
    var id = "";
    lists.forEach(function(list){
        lat = list.lat;
        lng = list.lng;
        if(mapInitFlag == false){
            mapInitFlag = true;
            initMap(lat,lng);
        }else{
            var marker = new L.marker([lat, lng]).addTo(map);
        }
    })
    $("#locate-report-modal").modal("show");
}

$("#locate-report-modal").on('shown.bs.modal', function(){
    setTimeout(function() {
        map.invalidateSize();
   }, 1);
})

function initMap(lat,lng){
    map = L.map("map-container").setView([lat,lng], 12);
    L.tileLayer('../../../system/skooltech_map/{z}/{x}/{y}.png', {
        maxZoom: 16,
        attribution: '© SkoolTech Solutions Map'
    }).addTo(map);
    var marker = new L.marker([lat, lng]).addTo(map);
}

function despatchReport(idx){
    if(confirm("Are you sure you want to despatch this report/request?")){
        reportIdx = idx;
        $.ajax({
            type: "POST",
            url: "despatch-report.php",
            dataType: 'html',
            data: {
                idx:reportIdx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    reportFilterChange();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function completeReport(idx){
    if(confirm("Are you sure you want to close this report/request and mark it as completed?")){
        reportIdx = idx;
        $.ajax({
            type: "POST",
            url: "complete-report.php",
            dataType: 'html',
            data: {
                idx:reportIdx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    reportFilterChange();
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