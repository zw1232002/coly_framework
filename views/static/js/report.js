/**
 * 设置日期函数
 * 
 * @param dateType 日期类型，today今天..
 */
function setDate(dateType){
   var startDate = '',endDate = '',curDate = new Date(),dataTool = new MrYangUtil();
    switch(dateType){
      case 'today':
        startDate = curDate.getFullYear()+"-"+(1+curDate.getMonth())+"-"+curDate.getDate();
        endDate = startDate;
        break;
      case 'yesterday':
        var dateArray = dataTool.getLast7Days(1);
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'last7days':
        var dateArray = dataTool.getLast7Days(7);
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'last15days':
        var dateArray = dataTool.getLast7Days(15);
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'last30days':
        var dateArray = dataTool.getLast7Days(30);
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'last60days':
        var dateArray = dataTool.getLast7Days(60);
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'curMonth':
        var dateArray = dataTool.getCurrentMonth();
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'lastweek':
        var dateArray = dataTool.getPreviousWeek();
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
      case 'lastmonth':
        var dateArray = dataTool.getPreviousMonth();
        startDate = dateArray[0].getFullYear()+"-"+(1+dateArray[0].getMonth())+"-"+dateArray[0].getDate();
        endDate =  dateArray[1].getFullYear()+"-"+(1+dateArray[1].getMonth())+"-"+dateArray[1].getDate();
        break;
    }
    $("#dropdown_btn").html($(this).html()+' <span class="caret"></span>');
    $('#startDate').val(startDate);
    $('#endDate').val(endDate);
    $("#quick_select").val($(this).html());
 }
/**
 * 检查表单
 */
function checkForm(){
  var startDate = $('#startDate').val();
  var endDate = $('#endDate').val();
  if(compare_time(startDate,endDate)===false){
    alert("开始日期不能大于结束日期！");
    return false;
  } 
  return true;
}

/**
 * 比较2个日期的大小
 * 
 * @param a 日期
 * @param b 日期
 * @returns {Boolean} a>b 返回false，a<b 返回true
 */
function compare_time(a,b) {
   var arr=a.split("-");
   var starttime=new Date(arr[0],arr[1],arr[2]);
   var starttimes=starttime.getTime();
   var arrs=b.split("-");
   var endtime=new Date(arrs[0],arrs[1],arrs[2]);
   var endtimes=endtime.getTime();
   if(starttimes>endtimes)//开始大于结束
   {
     return false;
   }
   else{
    return true;
   }
}
 jQuery(function ($){
   $('#startDate,#endDate').datetimepicker({
       language: 'zh-CN',
       format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });
});