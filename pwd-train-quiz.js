// quiz-navigation.js

function saveAnswer(question, answer) {
    localStorage.setItem(question, answer);
  }
  
  function loadAnswer(question) {
    return localStorage.getItem(question);
  }
  
  document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");
  
    forms.forEach((form) => {
      const question = form.querySelector("input[type='radio']").name;
  
      const savedAnswer = loadAnswer(question);
      if (savedAnswer) {
        const selectedOption = form.querySelector(`input[value='${savedAnswer}']`);
        if (selectedOption) {
          selectedOption.checked = true;
        }
      }
  
      form.addEventListener("change", function(event) {
        if (event.target.type === "radio") {
          saveAnswer(question, event.target.value);
        }
      });
    });
  });
  