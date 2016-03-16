/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//弹出警告
function _alert(title, content){
    html = '<div style="" id="dialog2" class="weui_dialog_alert">\
                <div class="weui_mask" style="z-index: 8"></div>\
                <div class="weui_dialog" style="z-index: 9">\
                    <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+title+'</strong></div>\
                    <div class="weui_dialog_bd" style="color:#ef4f4f;font-size:20px;">'+content+'</div>\
                    <div class="weui_dialog_ft">\
                        <a class="weui_btn_dialog primary" href="javascript:;">确定</a>\
                    </div>\
                </div>\
            </div>';
    $("body").append(html);
}

//操作成功页面
function _success(message, waitSecond){
    html = '<div id="dialog2" class="weui_dialog_alert">\
                <div class="weui_mask" style="z-index: 8"></div>\
                <div class="weui_dialog" style="z-index: 9">\
                    <div class="weui_dialog_hd"></div>\
                    <div class="weui_dialog_bd"><i class="weui_icon_msg weui_icon_success"></i></div>\
                    <div class="weui_dialog_ft">\
                        <a class="weui_btn_dialog primary" href="javascript:;">'+message+'</a>\
                    </div>\
                </div>\
            </div>';
    $("body").append(html);
    setTimeout(function(){
        $("#dialog2").remove();
    }, waitSecond*1000);
}

$(document).on("click", "#dialog2 .weui_btn_dialog", function(){
   $('#dialog2').remove();
});


