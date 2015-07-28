// JavaScript Document

startList = function() {
  if (document.all && document.getElementById) {
    navRoot = document.getElementById("nav_ul");
    for (i = 0; i < navRoot.childNodes.length; i++) {
      node = navRoot.childNodes[i];
      if (node.nodeName=="LI") {
        node.onmouseover = function() {
          this.className += " over";
        }
        node.onmouseout = function() {
          this.className = this.className.replace(" over", "");
        }
      }
    }

    dd = document.getElementsByTagName("ol");
    for (i = 0; i < dd.length; i++){
      for (j = 0; j < dd[i].childNodes.length; j++) {
        node = dd[i].childNodes[j];
        if (node.nodeName=="LI") {
          node.onmouseover = function() {
            this.className += " over";
          }
          node.onmouseout = function() {
            this.className = this.className.replace(" over", "");
          }
        }
      }
    }
  }
}
window.onload = startList;
