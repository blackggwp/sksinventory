$(document).ready(function() {    
    var isExportpdf = false;
    var cookieEmpcode = getCookie('empcode');
    var cookiePlant = getCookie('outletPlant');
    var keyType = getCookie('keytype');
    var outletBrand = getCookie('outletBrand');
    var outletCode = getCookie('outletCode');

    if ((cookieEmpcode == '') || (cookiePlant == '')) {
        $('.modalLogin').modal('toggle');
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

    // autosave when change
    $('.inputdateqty,.dropdownDepart,.dropdownGroup,.dropdownReason').change(function(event) {
            // loop value inputqty
        $('input[name^="mat_qty"]').each(function() {
            // check value inputqty
            if ($.inArray($(this).val(), ['', '0', 0]) == -1) {
                isedit = true;
            }
        });

        if (isedit) {
            saveToTemp(resetForm);
        }
        var ddval = $('.inputdateqty').val();
        requireDate(ddval);
        if (keyType == 'waste') {
            // console.log('key waste');
            var reasonValue = $('.dropdownReason').val();
            if ((reasonValue != 'undefined') && (reasonValue != null)) {
                loaddepart($(this));
            }
        }
        else {
            loaddepart($(this));
        }
        event.preventDefault();
    });


    $('.rptnav').click(function(event) {
        loadRpt();
    });
    $('#submitlogin').click(function(event) {
            // slice plant, outletCode and Brand
        var $outletPlant_Name_Code = $('.dropDownOutletCode').val();
        var $empcode = $('#empcode').val();
        var outletPlant_Name_CodeArr = $outletPlant_Name_Code.split('_',3);
        var $outletPlant = outletPlant_Name_CodeArr[0];
        var $Name = outletPlant_Name_CodeArr[1];
        var $outletCode = outletPlant_Name_CodeArr[2];
        var $outletBrand = '';
        
          // set outlet brand
        var outletKeywords = ['bbq','buffet','seoul'];
        var flagOutlet = '';
        for(i = 0;i < outletKeywords.length;i++) {
            if ($Name.toLowerCase().indexOf(outletKeywords[i]) !== -1){
                $outletBrand = outletKeywords[i];
                break;
            }
        }
        // check outlet can key
        if ($outletBrand == '') {
            alert('ขณะนี้เปิดให้ใช้งานได้เฉพาะ BQ, BF, SG กรุณาเลือกสาขาอีกครั้ง');
            event.preventDefault();
            // location.reload();
        }

        if (($empcode != '') && ($empcode.length == 6) 
            && ($outletPlant != null) && ($outletBrand != '')) {
                     // alert($empcode);
            if ($empcode.length == 6) {
                // alert($empcode);
                $.ajax({url: "./script/q_login.php?&empcode="+$empcode
                , type:'GET'
                , success: function(response){
                    var res = JSON.parse(response);
                    if (res['res'] == 'foundedUserID') {
                        checkLogin($empcode,$outletPlant,$outletBrand,$outletCode,event);
                    }else{
                        alert('รหัสพนักงานไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ');
                        return;
                    }
                }
            });
            }
        }
        
    });
    

    $('.logoutbtn').click(function(event) {
        setCookie('empcode','',-1);
        setCookie('outletPlant','',-1);
        setCookie('keytype','',-1);
        setCookie('outletBrand','',-1);
        setCookie('outletCode','',-1);
        location.reload();
    });

    // show gif loading while ajaxsend
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

function requireDate(data){

    if (data == '') {
        alert('กรุณาเลือกวันที่ก่อนครับ');
        event.preventDefault; //Stop Action
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
            requireDate(dsval);
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
            //         $.ajax({url: "./script/readfile.php",data:d + '&empcode=' +cookieEmpcode + '&plant=' +cookiePlant  + '&keytype='+keyType, success: function(response){
            //     // alert(response);
            //     console.log(response);
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
            requireDate(dsval);
            requireDate(deval);

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
            requireDate(dsval);
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
            requireDate(dsval);
            requireDate(deval);
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

function checkLogin($empcode,$outletPlant,$outletBrand,$outletCode,event) {
    setCookie('empcode',$empcode,1);
    setCookie('outletPlant',$outletPlant,1);
    setCookie('outletBrand',$outletBrand,1);
    setCookie('outletCode',$outletCode,1);

    $('.modalLogin').modal('toggle');
    event.preventDefault(); 
    window.location.replace("index.php");
}

function checkDate(){
    alert('กรุณาเลือกวันที่ก่อนครับ');
}

function senddateToWaste(){
    var date = $('#frmrpt').serialize();
        $.ajax({url: "./script/q_wasterpt.php?plant=" + cookiePlant
        , data:date
        , success: function(response){
            console.log(response);
            try {
                var dx = JSON.parse(response);
            // var dx = JSON.parse(JSON.stringify(response));
            } catch (error) {
                alert('ทำรายการการไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ \n'+error);
                location.reload();
            }
            tbldata = (dx['res']);
            
            // $('.exportPDF').click(function(event) {
            //     exportPDFFunc(tbldata);
            // });

            // datatable API
            // var table = $('.tblreport').DataTable({
            //     paging: false,
            //     "searching": false,
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'excel'
            //     ]
            // });

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
                    totalItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        columnAutoWidth: true,
                        customizeText: function (Cost) {
                            return 'Total ' + Cost.value.toFixed(2);
                        }
                    }],
                    groupItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        displayFormat: "{0}",
                        showInGroupFooter: true,
                        customizeText: function (Cost) {
                            return Cost.value.toFixed(2);
                        }
                    }]
                }
            });
            $('.result').html(dx['html']);
            }
        });
}

function senddateToWeek(){
    var date = $('#frmrpt').serialize();
        $.ajax({url: "./script/q_weekrpt.php?plant=" + cookiePlant
        , data:date
        , success: function(response){
            // console.log(response);
            try {
                var dx = JSON.parse(response);
            // var dx = JSON.parse(JSON.stringify(response));
            } catch (error) {
                alert('ทำรายการการไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ \n'+error);
                location.reload();
            }
            tbldata = (dx['res']);
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
                    totalItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        columnAutoWidth: true,
                        customizeText: function (Cost) {
                            return 'Total:' + Cost.value.toFixed(2);
                        }
                    }],
                    groupItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        displayFormat: "{0}",
                        showInGroupFooter: true,
                        customizeText: function (Cost) {
                            return Cost.value.toFixed(2);
                        }
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
        
        $.ajax({url: "./script/q_monthrpt.php?plant=" + cookiePlant,data:date, success: function(response){
            console.log(response);
            try {
                var dx = JSON.parse(response);
            // var dx = JSON.parse(JSON.stringify(response));
            } catch (error) {
                alert('ทำรายการการไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ \n'+error);
                location.reload();
            }
            tbldata = (dx['res']);
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
                    totalItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        customizeText: function (Cost) {
                            return 'Total ' + Cost.value.toFixed(2);
                        }
                    }],
                    groupItems: [{
                        column: "Cost",
                        summaryType: "sum",
                        valueFormat: "Numeric",
                        displayFormat: "{0}",
                        showInGroupFooter: true,
                        customizeText: function (Cost) {
                            return Cost.value.toFixed(2);
                        }
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
        
        $.ajax({url: "./script/q_sumweekrpt.php?plant=" + cookiePlant+'&empcode='+cookieEmpcode
        , data:date
        , success: function(response){
            var tbldata = response;
            $('.result').html(tbldata);
            // $('.exportPDF').click(function(event) {
            //     exportPDFFunc(tbldata);
            // });
        }
        });
}

function loaddepart($t){
    var $dropdown1 = $('.dropdownDepart').val();
    var $dateqty   = $('.inputdateqty').val();
    var $dropdownReason = $('.dropdownReason').val();
    var path = 'script/q_mat_depart.php?depart='+$dropdown1 + '&dateqty='+$dateqty + '&empcode=' +cookieEmpcode + '&plant=' 
    +cookiePlant+'&keytype='+keyType + '&brand=' + outletBrand + '&outletCode=' + outletCode
    if ($dropdownReason != '') { 
        path = path+'&reasonWaste='+$dropdownReason; 
    }
        $('.showresult').load(path,function(){
            filterTableAndInput();
        }); 
    }

function filterTableAndInput(){
    // Apply the filter
            // $(".example thead input").on( 'keyup change', function () {
            //     table
            //     .column( $(this).parent().index()+':visible' )
            //     .search( this.value )
            //     .draw();
                
            // } );

        // DataTable
        // var table = $('.example').DataTable({
        //     paging:         false,
        //     "searching": false,
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'excel'
        //     ]
        // });

        // for check duplicate keyPress
        var oneTimePressKey = {};
        $(".example .inputqty").keydown(function (e) {
            
            var char = e.keyCode || e.which;
            if (char == 13) { //Enter press for next input
                try{
                    // disable export button when enter press
                    // var table = $('.example').DataTable();
                    // table.buttons().disable();

                    var $inputMatQty = $(this).parent().parent().next().find('.inputqty').focus().select();
                    // var $inputMatQty = $(this).parent().parent().next().find('.inputqty').css("background-color", "yellow");

                    }
                catch(e){
                    console.log('Error: '+e);
                }
            };

            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(char, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (char === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl+F, Command+F
             (char === 70 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: Ctrl+R, Command+R
             (char === 82 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
             (char >= 35 && char <= 40)) {
                 // let it happen, don't do anything

                 // prevent -sign after enter number
                 if ((char === 109) || (char === 189)) {
                    e.preventDefault();
                 }
             return;
            }
            // Allow: -(substract sign)
            else if ((char === 109) || (char === 189)) {
                // prevent duplicate -sign
                if (oneTimePressKey[109] === true) {
                    e.preventDefault();
                } else if (oneTimePressKey[189] === true) {
                    e.preventDefault();
                } else {oneTimePressKey[e.keyCode] = ''}
                oneTimePressKey[e.keyCode] = e.type == 'keydown';
            }
            // Ensure that it is a number and stop the keypress
            else if ((e.shiftKey || (char < 48 || char > 57)) && (char < 96 || char > 105)) {
                e.preventDefault();
            }
            
        });
            
        $('.submitdata').click(function(event) {
            // window.alert('isedit = '+isedit);
            saveToTemp(resetForm);
                // isedit=false;
                // location.reload();
                // resetDepart(); 
        });
}

function saveToTemp(callb, event){
     // loop value inputqty
    $('input[name^="mat_qty"]').each(function() {
        // check value inputqty
        if ($.inArray($(this).val(), ['', '0', 0]) == -1) {
            isedit = true;
            // $(this).css('background-color','red');
            $(this).addClass('tdischange');
            $(this).next().addClass('tdischange');
            $(this).parent().parent().addClass('trischange');
        }
    });
    // window.alert('isedit in savetotemp func = '+isedit);
    if(!isedit){
        callb();
    }
    
    // var isfound=false;
    // var $g=$('.tdqty:not(.tdischange)');
    // var val='';
    // window.alert('isfound in savetotemp func = '+isfound);
    // if(!isfound){
        // callb();
    // }

    $('.inputqty:not(.tdischange)').remove();
    $('.trqty:not(.trischange)').remove();

    
    var $dateqty   = $('.inputdateqty').val();
    var d = $('.form_data').serialize();
    // console.log(d);
    if (d != '') {
        $.ajax({url: "./script/savetotemp.php"
            , data:d + '&empcode=' +cookieEmpcode + '&plant=' +cookiePlant + '&dateqty='+$dateqty + '&keytype='+keyType
            , type:'POST'
            , success: function(response){
        // alert(response);
        console.log(response);
        callb();
        isedit = false;
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
        success: function(response){
            console.log(response);
            callb(response,response);
           
        },
        error: function(response){
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
    // $('.example input[type="number"]').val('');
    // window.alert('resetForm func exec');
    document.getElementById('form_inputqty').reset();
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
                data:send,  success: function(response2){
                    isExportpdf = true;
                    console.log(response2);
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
                data:'&ajdata=' + 'test',  success: function(response2){
                    console.log(response2);
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
        var char = e.keyCode || e.which;
            if (char == 13) { //Enter press for next input
                try{
                    var $inputMatQty = $(this).next().next().next().focus().select();
                    }
                catch(e){}
            };

            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(char, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (char === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
             (char >= 35 && char <= 40)) {
                 // let it happen, don't do anything
             return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (char < 48 || char > 57)) && (char < 96 || char > 105)) {
            e.preventDefault();
            }
        });
}

});


