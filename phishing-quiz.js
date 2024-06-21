// Function to save quiz answers to localStorage
function submitQuiz() {
    const answers = {};
    for (let i = 1; i <= 5; i++) {
      const answer = document.querySelector(`input[name="q${i}"]:checked`);
      if (answer) {
        answers[`q${i}`] = answer.value;
        localStorage.setItem(`q${i}`, answer.value);
      }
    }
  }
  
  // Function to display results
  function calculateResults() {
    const correctAnswers = {
      q1: "c",
      q2: "b",
      q3: "b",
      q4: "b",
      q5: "b"
    };
    let score = 0;
    let totalQuestions = 5;
    
    for (let i = 1; i <= totalQuestions; i++) {
      let userAnswer = localStorage.getItem("q" + i);
      if (userAnswer === correctAnswers["q" + i]) {
        score++;
        document.getElementById("result-q" + i).innerHTML = "Question " + i + ": Correct";
      } else {
        document.getElementById("result-q" + i).innerHTML = "Question " + i + ": Incorrect";
      }
    }
  
    document.getElementById("final-score").innerHTML = "You scored " + score + " out of " + totalQuestions;
  }
  
  window.onload = function() {
    calculateResults();
  }
  