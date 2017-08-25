/*规则说明：
如：input type="email" value="" name="title" class="fn-tinput" required data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"
required //必填
data-rule-email="true" //正则为 email
data-msg-required="请输入您的电子邮箱" //为空时的提示信息
data-msg-email="邮箱格式不正确" //不符合正则的提示
placeholder="电子邮件" //input中没有填写的提示
*/
$().ready(function() { 
    $("#jsForm").validate({
        submitHandler:function(form){ 
            form.submit(); 
        } 
    }); 
}); 
//下面是一些常用的验证规则扩展

/*-------------验证插件配置-------------*/

//配置错误提示的节点，默认为label，这里配置成 span （errorElement:'span'）
$.validator.setDefaults({
	errorElement:'span'
});

//配置通用的默认提示语
/*(1)required:true 必输字段
(2)remote:”check.php” 使用ajax方法调用check.php验证输入值
(3)email:true 必须输入正确格式的电子邮件
(4)url:true 必须输入正确格式的网址
(5)date:true 必须输入正确格式的日期
(6)dateISO:true 必须输入正确格式的日期(ISO)，例如：2009-06-23，1998/01/22 只验证格式，不验证有效性
(7)number:true 必须输入合法的数字(负数，小数)
(8)digits:true 必须输入整数
(9)creditcard: 必须输入合法的信用卡号
(10)equalTo:”#field” 输入值必须和#field相同
(11)accept: 输入拥有合法后缀名的字符串（上传文件的后缀）
(12)maxlength:5 输入长度最多是5的字符串(汉字算一个字符)
(13)minlength:10 输入长度最小是10的字符串(汉字算一个字符)
(14)rangelength:[5,10] 输入长度必须介于 5 和 10 之间的字符串”)(汉字算一个字符)
(15)range:[5,10] 输入值必须介于 5 和 10 之间
(16)max:5 输入值不能大于5
(17)min:10 输入值不能小于10*/
$.extend($.validator.messages, {
	required: '必填',
    equalTo: "请再次输入相同的值",
    remote: "请修正该字段",
    email: "电子邮件格式不正确",
    url: "网址格式不正确",
    date: "日期格式不正确",
    dateISO: "请输入合法的日期 (ISO).",
    number: "请输入数字",
    digits: "只能输入数字",
    creditcard: "请输入合法的信用卡号",
    equalTo: "请再次输入相同的值",
    accept: "请输入拥有合法后缀名的字符",
    maxlength: $.validator.format("字符长度过长"),
    minlength: $.validator.format("字符长度过短"),
    rangelength: $.validator.format("字符长度超出范围"),
    range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
    max: $.validator.format("数值过大"),
    min: $.validator.format("数值过小")
});

/*-------------扩展验证规则 ------------*/
//邮箱 
jQuery.validator.addMethod("mail", function (value, element) {
	var mail = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/;
	return this.optional(element) || (mail.test(value));
}, "邮箱格式不对");

//电话验证规则
jQuery.validator.addMethod("phone", function (value, element) {
    var phone = /^0\d{2,3}-\d{7,8}$/;
    return this.optional(element) || (phone.test(value));
}, "电话格式如：0371-68787027");

//无区号电话验证规则  
jQuery.validator.addMethod("noactel", function (value, element) {
    var noactel = /^\d{7,8}$/;
    return this.optional(element) || (noactel.test(value));
}, "电话格式如：68787027");

//手机验证规则  
jQuery.validator.addMethod("tel", function (value, element) {
    var tel = /^1[3|4|5|7|8]\d{9}$/;
	return this.optional(element) || (tel.test(value));
}, "手机格式不对");

//邮箱或手机验证规则  
jQuery.validator.addMethod("mm", function (value, element) {
    var mm = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$|^1[3|4|5|7|8]\d{9}$/;
	return this.optional(element) || (mm.test(value));
}, "格式不对");

//电话或手机验证规则  
jQuery.validator.addMethod("tm", function (value, element) {
    var tm=/(^1[3|4|5|7|8]\d{9}$)|(^\d{3,4}-\d{7,8}$)|(^\d{7,8}$)|(^\d{3,4}-\d{7,8}-\d{1,4}$)|(^\d{7,8}-\d{1,4}$)/;
    return this.optional(element) || (tm.test(value));
}, "格式不对");

//年龄
jQuery.validator.addMethod("age", function(value, element) {   
	var age = /^(?:[1-9][0-9]?|1[01][0-9]|120)$/;
	return this.optional(element) || (age.test(value));
}, "不能超过120岁"); 
///// 20-60   /^([2-5]\d)|60$/

//传真
jQuery.validator.addMethod("fax",function(value,element){
    var fax = /^(\d{3,4})?[-]?\d{7,8}$/;
    return this.optional(element) || (fax.test(value));
},"传真格式如：0371-68787027");

//验证当前值和目标val的值相等 相等返回为 false
jQuery.validator.addMethod("equalTo2",function(value, element){
    var returnVal = true;
    var id = $(element).attr("data-rule-equalto2");
    var targetVal = $(id).val();
    if(value === targetVal){
        returnVal = false;
    }
    return returnVal;
},"不能和原始密码相同");

//大于指定数
jQuery.validator.addMethod("gt",function(value, element){
    var returnVal = false;
    var gt = $(element).data("gt");
    if(value > gt && value != ""){
        returnVal = true;
    }
    return returnVal;
},"不能小于0 或空");

//汉字
jQuery.validator.addMethod("chinese", function (value, element) {
    var chinese = /^[\u4E00-\u9FFF]+$/;
    return this.optional(element) || (chinese.test(value));
}, "格式不对");

//指定数字的整数倍
jQuery.validator.addMethod("times", function (value, element) {
    var returnVal = true;
    var base=$(element).attr('data-rule-times');
    if(value%base!=0){
        returnVal=false;
    }
    return returnVal;
}, "必须是发布的整数倍");

//身份证
jQuery.validator.addMethod("ident", function (value, element) {
    var isident1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;//(15位)
    var isident2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;//(18位)

    return this.optional(element) || (isident1.test(value)) || (isident2.test(value));
}, "格式不对");

//用户编码,只允许数字和英文,至少6位
jQuery.validator.addMethod("usernumber", function (value, element) {
    var usernumber=/^[a-zA-Z0-9_]{4,12}$/ ;
    return this.optional(element) || (usernumber.test(value));
}, "格式不对");

//密码,至少6位
jQuery.validator.addMethod("password", function (value, element) {
    var password=/^[a-zA-Z0-9_!@#$%&.]{4,20}$/ ;
    return this.optional(element) || (password.test(value));
}, "格式不对");

//xicode院系代码
jQuery.validator.addMethod("xicode", function (value, element) {
    var xicode=/^[a-zA-Z0-9]{3,12}$/ ;
    return this.optional(element) || (xicode.test(value));
}, "格式不对");