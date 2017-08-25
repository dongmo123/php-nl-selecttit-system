var winWidth = 0;
var winHeight = 0;
var content=document.getElementById('content');
var main=document.getElementById('main');
var iframe=document.getElementById('iframe');
var left=document.getElementById('left');
left.style.display='block';
function findDimensions(){//函数：获取尺寸
    //获取窗口宽度
    if (window.innerWidth)
    winWidth = window.innerWidth;
    else if ((document.body) && (document.body.clientWidth))
    winWidth = document.body.clientWidth;
    //获取窗口高度
    if (window.innerHeight)
    winHeight = window.innerHeight;
    else if ((document.body) && (document.body.clientHeight))
    winHeight = document.body.clientHeight;
    //通过深入Document内部对body进行检测，获取窗口大小
    if (document.documentElement  && document.documentElement.clientHeight && document.documentElement.clientWidth)
    {
    winHeight = document.documentElement.clientHeight;
    winWidth = document.documentElement.clientWidth;
    }
    //结果输出至名为content的div,改变其高度
    content.style.height=(winHeight-117)+'px';
    main.style.width=(winWidth-left.clientWidth-18)+'px';
    //兼容360
    iframe.style.height=main.clientHeight+'px';
}
findDimensions();//这是函数名
//调用函数，获取数值
window.onresize=findDimensions;// 当改变窗口大小的时候会触发这个事件

console.log('显示/隐藏左侧菜单功能已开启');
var i=0;
function switchbar(){
    if(i%2==1){
        left.style.display='none';
        switchimg.src="imgs/pic24.jpg";
        switchimg.title='显示左侧菜单';
        findDimensions();
    }else{
        left.style.display='block';
        switchimg.src="imgs/pic23.jpg";
        switchimg.title='隐藏左侧菜单';
        findDimensions();
    }
}