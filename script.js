document.addEventListener('DOMContentLoaded', () => {

  var disclaimer =  document.querySelector("img[alt='www.000webhost.com']");

   if(disclaimer){
       disclaimer.remove();
   }  
 });


function submitCode() {
        var codeInput = document.getElementById("code-input").value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_code.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("code-output").innerHTML = xhr.responseText;
          }
        };
        xhr.send("code=" + encodeURIComponent(codeInput));
      }