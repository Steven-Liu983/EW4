function Clear() {
  alert("The form is cleared");
}

function validate() {
    var name=document.myform.name.value;
    var email=document.myform.email.value;
    var comment=document.myform.comment.value;
    
    if (name==null || name==""){
      alert("Name cannot be blank");
      return false;
    }
    else if (email==null || email==""){
      alert("Email cannot be blank");
      return false;
    }
    else if (comment==null || comment==""){
      alert("Comment section cannot be blank");
      return false;
    }
    else {
      alert("The form is submitted");
    }
}
