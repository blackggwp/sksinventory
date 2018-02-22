$(document).ready(function() {    
    var isExportpdf = false;
    var cookieEmpcode = getCookie('empcode');
    var cookiePlant = getCookie('plant');
    var keyType = getCookie('keytype');

    if ((cookieEmpcode == '') || (cookiePlant == '')) {
        $('.modalLogin').modal('toggle');

        // prevent enter key when not yet enter empcode and plant
        // $(':input.empcode').keypress(function(e) {
        //     if(e.which == 13) {
        //         $('.submitlogin').click();
        //             e.preventDefault();
        //     }
        // });
        
    }

    var isedit=false;
    
    if (keyType == 'ending') {
        $(".inputdateqty").datepicker(
            { 
                dateFormat:"dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                beforeShowDay: function(day) {
                var day = day.getDay();
                    if (day == 1) {
                        return [true, "somecssclass"]
                    }else {
                        return [false, "someothercssclass"]
                    }
            }
        });
    }

    if (keyType == 'waste') {
        $(".inputdateqty").datepicker({
            dateFormat:"dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });
    }

    $('.invenbtn').click(function(event) {
        setCookie('keytype','',-1);
        var keyType = '';
        keyType = 'ending';
        setCookie('keytype',keyType,1);

        window.location.replace("ending.php?keytype=" + keyType);
    });

    $('.lossbtn').click(function(event) {
        setCookie('keytype','',-1);
        var keyType = '';
        keyType = 'waste';
        setCookie('keytype',keyType,1);

        window.location.replace("waste.php?keytype=" + keyType);
        
    });

    $('.rptmainbtn').click(function(event) {
        setCookie('keytype','',-1);
        var keyType = '';
        loadRpt();
    });

    $('.dropdownDepart,.dropdownGroup,.inputdateqty').change(function(event) {
        // loop value inputqty
        // $('input[name^="mat_qty"]').each(function() {
        //     // check value inputqty
        //     if ($.inArray($(this).val(), ['', '0', 0]) == -1) {
        //         // console.log($(this).val());
                
        //         saveToTemp(function(){
        //             isedit=false;
        //         });
        //     }
        // });
        var ddval = $('.inputdateqty').val();
        chkval(ddval);
        loaddepart($(this));
    });


    $('.rptnav').click(function(event) {
        loadRpt();
    });
    $('#submitlogin').click(function(event) {
        var $plant = $('.dropDownOutletCode').val();
        var $empcode = $('.empcode').val();
        if (($empcode != '') && ($empcode.length == 6) && ($plant != null)) {
            checkLogin($empcode,$plant);
        }
    });

    $('.logoutbtn').click(function(event) {
        setCookie('empcode','',-1);
        setCookie('plant','',-1);
        setCookie('keytype','',-1);
        location.reload();
    });

    $(document).ajaxSend(function(event, request, settings) {
        $('#loading-indicator').show();
        $('.showresult').addClass('loading');
        $('#tblreport').addClass('loading');
    });
    $(document).ajaxComplete(function(event, request, settings) {
        $('#loading-indicator').hide();
        $('.showresult').removeClass('loading');
        $('#tblreport').removeClass('loading');
    });
    // select2
    $('.dropDownOutletCode').select2();

function chkval(data){

    if (data == '') {
        alert('กรุณาเลือกวันที่ก่อนครับ');
        preventDefault; //Stop Action
    }else{
        return;
    }
}

function loadRptSumMonth(){
    setCookie('keytype','',-1);
    $('#main').load('form/summonthreport.php',function(){
        $('.showrptbtn').click(function(event) {
            senddateToMonth();
        });
    });
}

function loadRptSumWeek(){
    setCookie('keytype','',-1);
    $('#main').load('form/sumweekreport.php',function(){
        var inputsum = $('.inputsum');
        isEnterKey(inputsum);
        $('.datestart').datepicker(
        { 
            dateFormat:'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: function(day) {
            var day = day.getDay();
            if (day == 1) {
                return [true, "somecssclass"]
            } else {
                return [false, "someothercssclass"]
            }
         }
        });
        $('.showrptbtn').click(function(event) {
            var dsval = $('.datestart').val(); 
            chkval(dsval);
            senddateToSumWeek();

        });
    });
}

function loadUploadPage(){
    setCookie('keytype','',-1);
    $('#main').load('form/upload.php',function(){
        $('.uploadbtn').click(function(event) {

            // var d = $('#fileToUpload').val();
            //     if (d != '') {
            //         $.ajax({url: "./script/readfile.php",data:d + '&empcode=' +cookieEmpcode + '&plant=' +cookiePlant  + '&keytype='+keyType, success: function(r){
            //     // alert(r);
            //     console.log(r);
            //     // callb();
            // alert(d);

            //             }
            //         });
            //     }else{
            //         alert('Please choose file to upload.');
            //     }
        });
    });
}

function loadWasteRpt(){
    setCookie('keytype','',-1);
    $('#main').load('form/wastereport.php',function(){
        var dst = $('.datestart');
        var de = $('.dateend');
        isDatePicker(dst);

            $('.datestart').change(function(){
            var dsval = $('.datestart').val(); 
            $('.dateend').datepicker({
                minDate: dsval,
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                selectOtherMonths: true
            });
                $('.dateend').focus().preventDefault();
            });
        
        $('.showrptbtn').click(function(event) {
            var dsval = $('.datestart').val();
            var deval = $('.dateend').val();
            chkval(dsval);
            chkval(deval);

            senddateToWaste();
            // alert(dsval);
        });
    });
}

function loadRpt(){
    setCookie('keytype','',-1);
    $('#main').load('form/subreport.php',function(){
        $('.weekrptbtn').click(function(event) {
            loadWeekRpt();
        });
        $('.monthrptbtn').click(function(event) {
            loadMonthRpt();
        });
        $('.wasterptbtn').click(function(event) {
            loadWasteRpt();
        });
        $('.sumweekrptbtn').click(function(event) {
            loadRptSumWeek();
        });
        $('.summonthrptbtn').click(function(event) {
            loadRptSumMonth();
        });
        
    });
}

function loadWeekRpt(){
    setCookie('keytype','',-1);
    $('#main').load('form/weekreport.php',function(){
        $('.datestart').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: function(day) {
                var day = day.getDay();
                if (day == 1) {
                    return [true, "somecssclass"]
                } else {
                    return [false, "someothercssclass"]
                }
            }
        });
        $('.showrptbtn').click(function(event) {
            var dsval = $('.datestart').val(); 
            chkval(dsval);
            senddateToWeek();

        });
        
    });
}
function loadMonthRpt(){
    setCookie('keytype','',-1);
    $('#main').load('form/monthreport.php',function(){
    // var dst = $('.datestart');
    // var de = $('.dateend');
    //     isDatePicker(dst);

    //     $('.datestart').change(function(){
    //     var dsval = $('.datestart').val(); 
    //     $('.dateend').datepicker({
    //         minDate: dsval,
    //         dateFormat: 'dd-mm-yy',
            // changeMonth: true,
            // changeYear: true
    //     });
    //         $('.dateend').focus().preventDefault();
    //     });

    $('.datestart').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy'
        
    }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
thisCalendar.datepicker('setDate', new Date(year, month, 1));
    });

});
        $('.showrptbtn').click(function(event) {
            var dsval = $('.datestart').val();
            var deval = $('.dateend').val();
            chkval(dsval);
            chkval(deval);
            senddateToMonth();
        });    
    });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkLogin($empcode,$plant){
    setCookie('empcode',$empcode,1);
    setCookie('plant',$plant,1);
    $('.modalLogin').modal('toggle');

    event.preventDefault(); 
    window.location.replace("index.php");
}

function checkDate(){
    alert('กรุณาเลือกวันที่ก่อนครับ');
}

function senddateToWaste(){
    var date = $('#frmrpt').serialize();
        $.ajax({url: "./script/q_wasterpt.php?plant=" + cookiePlant,data:date, success: function(r){
            var tbldata = r;
            $('.result').html(tbldata);
            // $('.exportPDF').click(function(event) {
            //     exportPDFFunc(tbldata);
            // });
            var table = $('.tblreport').DataTable({
                paging: false,
                "searching": false,
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
            }
        });
}

function senddateToWeek(){
    var date = $('#frmrpt').serialize();
        
        $.ajax({url: "./script/q_weekrpt.php?plant=" + cookiePlant,data:date, success: function(r){
            // var tbldata = r;
            var dx = JSON.parse(r);
            tbldata = (dx['res']);

        //   var arr = ['Beg', 'Code', 'Name'];
            $('#tblreport').dxDataGrid({ ////Devexpress
                selection: {
                    mode: "multiple"
                },
                "export": {
                    enabled: true,
                    fileName: "matmg",
                    allowExportSelectedData: true
                },
                dataSource:dx['res'],
                paging:false,
                allowColumnResizing:true,
                groupPanel: {
                    visible: true
                },
                searchPanel: {
                    visible: true,
                    width: 240,
                    placeholder: "Search..."
                },
                columns: 
                    dx['colName']
                ,
                summary: {
                    groupItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        displayFormat: "{0}",
                        showInGroupFooter: true
                    }]
                }
            });
            $('.result').html(dx['html']);

            // $('.exportPDF').click(function(event) {
            //     var tbldata = $('#tblreport').html();
            //     // console.log(tbldata);
            //     exportPDFFunc(tbldata);
            // });
        }
        });
}

function senddateToMonth(){
    var date = $('#frmrpt').serialize();
        
        $.ajax({url: "./script/q_monthrpt.php?plant=" + cookiePlant,data:date, success: function(r){
            // var tbldata = r;
            var dx = JSON.parse(r);
            tbldata = (dx['res']);

          var arr = ['Beg', 'Code', 'Name'];
            $('#tblreport').dxDataGrid({ ////Devexpress
                selection: {
                    mode: "multiple"
                },
                "export": {
                    enabled: true,
                    fileName: "matmg",
                    allowExportSelectedData: true
                },
                dataSource:dx['res'],
                paging:false,
                allowColumnResizing:true,
                groupPanel: {
                    visible: true
                },
                searchPanel: {
                    visible: true,
                    width: 240,
                    placeholder: "Search..."
                },
                columns: 
                    dx['colName']
                ,
                summary: {
                    groupItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        // valueFormat: "currency",
                        displayFormat: "{0}",
                        showInGroupFooter: true
                    }]
                }
            });
            $('.result').html(dx['html']);

            // $('.exportPDF').click(function(event) {
            //     var tbldata = $('#tblreport').html();
            //     // console.log(tbldata);
            //     exportPDFFunc(tbldata);
            // });
            }

        });
}

function senddateToSumWeek(){
    var date = $('#frmrpt').serialize();
        
        $.ajax({url: "./script/q_sumweekrpt.php?plant=" + cookiePlant+'&empcode='+cookieEmpcode,data:date, success: function(r){
            var tbldata = r;
            $('.result').html(tbldata);
            // $('.exportPDF').click(function(event) {
            //     exportPDFFunc(tbldata);
            // });
        }
        });
}

function loaddepart($t){
    var $dropdown1 = $('.dropdownDepart').val();
    var $dropdown2 = $('.dropdownGroup').val();
    var $dateqty   = $('.inputdateqty').val();

        $('.showresult').load('script/q_mat_depart.php?depart='+$dropdown1 + '&group='+$dropdown2 + '&dateqty='+$dateqty + '&empcode=' +cookieEmpcode + '&plant=' +cookiePlant+'&keytype='+keyType,function(){
            filterTableAndInput();
        }); 
    }

function filterTableAndInput(){
    // Apply the filter
            $(".example thead input").on( 'keyup change', function () {
                table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
                
            } );

        // DataTable
        var table = $('.example').DataTable({
            paging:         false,
            "searching": false,
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        });

        $(".example .inputqty").keydown(function (e) {
            isedit = true;
            $(this).addClass('tdischange');
            $(this).next().addClass('tdischange');
            $(this).parent().parent().addClass('trischange');

            if (e.keyCode == 13) { //Enter press for next input
                try{
                    // disable export button when enter press
                    var table = $('.example').DataTable();
                    table.buttons().disable();

                    var $inputMatQty = $(this).parent().parent().next().find('.inputqty').focus().select();
                    // var $inputMatQty = $(this).parent().parent().next().find('.inputqty').css("background-color", "yellow");

                    }
                catch(e){
                    console.log('Error: '+e);
                }
            };

            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl+F, Command+F
             (e.keyCode === 70 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
             (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
             return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
            }
        });
            
        $('.submitdata').click(function(event) {
            saveToTemp(function(){
                        // location.reload();
                        // resetForm();
                        resetDepart();
            });
        });
}

function saveToTemp(callb){

    if(!isedit){
        callb();
    }
    var isfound=false;
    var $g=$('.tdqty:not(.tdischange)');

    var val='';

    $('.inputqty:not(.tdischange)').remove();
    $('.trqty:not(.trischange)').remove();
      
    if(!isfound){
        callb();
    }
    var $dateqty   = $('.inputdateqty').val();
    var d = $('.form_data').serialize();

    if (d != '') {
        $.ajax({url: "./script/savetotemp.php",data:d + '&empcode=' +cookieEmpcode + '&plant=' +cookiePlant + '&dateqty='+$dateqty + '&keytype='+keyType, success: function(r){
        // alert(r);
        console.log(r);
        callb();
            }
        });
    };

}

function confirmdatadepart(callb){
    $( ".dialog-confirmdatadepart" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        OK: function() {
          $( this ).dialog( "close" );
          savetodb(function(r1,r2){
            // alert(r1);
            callb();
          });
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
}

function savetodb(callb){
    $.ajax({
        url: 'script/savetodb.php',
        success: function(r){
            console.log(r);
            callb(r,r);
           
        },
        error: function(r){
            alert('Save to Database failed : ' + r);
        }
    })
    .done(function() {
        console.log("success");
    })
    .fail(function() {
        console.log("error");
    });
}

function remsg(callb){
    $( ".dialog-message" ).dialog({
      modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
          callb();
        }
      }
    });
}

function resetForm() {
    $('.example input[type="number"]').val('');
}

function resetDepart() {
    // $('.dropdownDepart').val('').change();
    $('.dropdownDepart').prop("selectedIndex", 0).change();
}

function exportPDFFunc(tbldata) {
    var gen = Date.now();
    // var redirectWindow = window.open('export/inven.pdf','_blank');
    isExportpdf = false;
    // console.log(tbldata);
    var send={};
    send['html']=tbldata;

    $.ajax({
                url: './script/exportpdf.php',
                type:'POST',
                data:send,  success: function(r2){
                    isExportpdf = true;
                    console.log(r2);
                    // redirectWindow.location.reload();
                    // window.location.reload(true);
                viewPdf(0);
                }

            });
    
}
function viewPdf(retry){

    if (retry >= 10) {
        alert('นานเกินไป กรุณาทำรายการใหม่');
        return;
    };
    setTimeout(function(){
        // alert('in settimeout'+isExportpdf);
        if (isExportpdf) {
            // var redirectWindow = window.open('export/inven.pdf','_blank', 'clearcache=yes');
            window.open('export/inven.pdf','_blank');
            setTimeout(function(){
                delPdf(); //****************Delete PDF file on server
            },3000);
        }else
        {
            viewPdf(retry+1);
        };
            },500);
}

function delPdf(){
    $.ajax({
                url: './script/delpdf.php',
                type:'POST',
                data:'&ajdata=' + 'test',  success: function(r2){
                    console.log(r2);
                    // redirectWindow.location;
                }
            });
}
function isDatePicker(dpk){
    $( dpk ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true
    });
}
function isEnterKey(data){
    $( data ).keydown(function (e) {

            if (e.keyCode == 13) { //Enter press for next input
                try{
                    var $inputMatQty = $(this).next().next().next().focus().select();
                    }
                catch(e){}
            };

            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
             (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
             return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
            }
        });
}

});


