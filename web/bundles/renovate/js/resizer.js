Resizer = {
    attach: function(el,minh){
        var rs=el.resizer=el.getElementsByClassName('handler_horizontal')[0];
        rs.resizeParent=el;
        el.minh=minh||rs.offsetHeight*10;
        
        rs.onmousedown = Resizer.begin;
    },
    begin: function(e){
        var el=Resizer.el=this.resizeParent;
        var e=e||window.event;
        this.lasty=e.clientY;
        
        document.onmousemove=Resizer.resize;
        document.onmouseup=Resizer.end;
        return false;
    },
    resize: function(e){
        var e = e || window.event;
        var y,my,el,rs,newh;
        el=Resizer.el;
        rs=el.resizer;
        my=e.clientY;
        newh=(el.clientHeight-(rs.lasty-my));
        
        console.log(newh,my);
        
        if(newh>=el.minh){
            el.style.height=newh+'px';
            rs.lasty=my;
        }
        else{
            rs.lasty-=parseInt(el.style.height)-el.minh;
            el.style.height=el.minh+'px';
        }
  
        return false;
    },
    end: function(){
        document.onmouseup=null;
        document.onmousemove=null;
    }
};