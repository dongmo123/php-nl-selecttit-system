/*左侧菜单栏伸缩特效*/
var on=document.getElementsByClassName('on');
/*添加点击事件*/
for(var i;i<on.length;i++){
	on[i].onclick=function(){
		var nextdiv=this.nextSibling.nextSibling;
		var maxheight=nextdiv.scrollHeight;
		if(nextdiv.clientHeight!==0){
			nextdiv.style.height=0+'px';
			this.style.backgroundImage='url("./imgs/collapsed.jpg")';
		}else if(nextdiv.clientHeight==0){
			nextdiv.style.height=maxheight+'px';
			this.style.backgroundImage='url("./imgs/expanded.jpg")';
		}
	}
}