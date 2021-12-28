var btn = $(".submit-btn");
var container = $(".card-container");
btn.on("click", appendToList);
container.on("click", deleteCard);

function appendToList(event) {
  event.preventDefault();
  var name_form = $(".name");
  var email_form = $(".email");
  var comment_form = $(".comment");
  var comment = $(".comment").val();
  var name = $(".name").val();

  feedback = $("input[name='feedback']");
  feedbackGet = feedback.get(0);
  myFeedback = feedbackGet.value;

  if (name == "" && myFeedback) {
    name = "Anonymous";
  }

  container.append(`
   <div class="card">
     <p id="p"class="task">"${comment}" </p>
     <p id="p" class="task"> - ${name} </p>
     <button class="delete"> Delete! </button>
     </br>
   </div>
 `);
  $(".card").fadeIn("slow");
  name_form.val("");
  email_form.val("");
  comment_form.val("");
}
function deleteCard() {
  if (event.target.className === "delete") {
    event.target.parentNode.remove();
  }
}
