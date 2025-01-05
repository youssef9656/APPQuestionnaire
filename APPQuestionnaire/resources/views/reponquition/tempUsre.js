
    function toggleMandatoryOptions(optionId) {
    document.querySelectorAll('.mandatory-options').forEach(div => div.style.display = 'none');
    const mandatoryDiv = document.getElementById(`mandatory-${optionId}`);
    if (mandatoryDiv) {
    mandatoryDiv.style.display = 'block';
}
}
    document.addEventListener("DOMContentLoaded", () => {
    const questions = document.querySelectorAll(".question");
    const submitButton = document.querySelector(".btn-validate");
    const progressText = document.querySelector(".progress-text");
    const progressCircle = document.querySelector(".circular-progress");

    let currentQuestionIndex = 0;

    // Met à jour la barre de progression
    function updateProgress() {
    const totalQuestions = questions.length;
    const progressPercentage = ((currentQuestionIndex + 1) / totalQuestions) * 100;
    progressText.textContent = `${currentQuestionIndex + 1}/${totalQuestions}`;
    progressCircle.style.background = `conic-gradient(#0d6efd ${progressPercentage}%, #e0e0e0 ${progressPercentage}% 100%)`;
}

    // Cache toutes les questions sauf la première
    questions.forEach((question, index) => {
    if (index !== 0) question.style.display = "none";
});

    // Cache le bouton "Soumettre les réponses" jusqu'à la dernière question
    submitButton.style.display = "none";

    // Ajoute les boutons "Précédent" et "Suivant" à chaque question
    questions.forEach((question, index) => {
    const buttonContainer = document.createElement("div");
    buttonContainer.className = "button-container mt-3";

    // Bouton "Précédent"
    if (index > 0) {
    const prevButton = document.createElement("button");
    prevButton.textContent = "Précédent";
    prevButton.type = "button";
    prevButton.className = "btn btn-secondary me-2";
    prevButton.onclick = () => {
    showPreviousQuestion();
    updateProgress(); // Met à jour la progression lors du retour
};
    buttonContainer.appendChild(prevButton);
}

    // Bouton "Suivant"
    if (index < questions.length - 1) {
    const nextButton = document.createElement("button");
    nextButton.textContent = "Suivant";
    nextButton.type = "button";
    nextButton.className = "btn btn-primary";
    nextButton.onclick = () => {
    showNextQuestion();
    updateProgress(); // Met à jour la progression lors de l'avancement
};
    buttonContainer.appendChild(nextButton);
}

    question.appendChild(buttonContainer);
});

    // Fonction pour afficher la question suivante
    function showNextQuestion() {
    const currentQuestion = questions[currentQuestionIndex];
    const nextQuestion = questions[currentQuestionIndex + 1];

    const inputs = currentQuestion.querySelectorAll("input[required]");
    const allAnswered = Array.from(inputs).every(input => input.checkValidity());

    if (allAnswered) {
    currentQuestion.style.display = "none"; // Cache la question actuelle
    nextQuestion.style.display = "block"; // Affiche la question suivante
    currentQuestionIndex++; // Met à jour l'index de la question actuelle

    // Si c'est la dernière question, affiche le bouton "Soumettre les réponses"
    if (currentQuestionIndex === questions.length - 1) {
    submitButton.style.display = "block";
}
} else {
    alert("Veuillez répondre à toutes les questions avant de continuer.");
}
}

    // Fonction pour afficher la question précédente
    function showPreviousQuestion() {
    const currentQuestion = questions[currentQuestionIndex];
    const previousQuestion = questions[currentQuestionIndex - 1];

    currentQuestion.style.display = "none"; // Cache la question actuelle
    previousQuestion.style.display = "block"; // Affiche la question précédente
    currentQuestionIndex--; // Met à jour l'index de la question actuelle

    // Cache le bouton "Soumettre les réponses" si ce n'est pas la dernière question
    submitButton.style.display = "none";
}

    // Met à jour la progression lors du chargement de la page
    updateProgress();
});
    document.addEventListener("DOMContentLoaded", () => {
    const submitButton = document.querySelector(".btn-validate");
    const timerElement = document.getElementById("timer");
    let timerDuration = parseInt(timerElement.getAttribute("data-duration"));
    let timerInterval;

    // بدء العد التنازلي
    function startTimer() {
    timerInterval = setInterval(() => {
    if (timerDuration <= 0) {
    clearInterval(timerInterval);
    handleTimeOut();
} else {
    timerDuration--;
    updateTimerDisplay();
}
}, 1000);
}

    // تحديث العرض للعد التنازلي
    function updateTimerDisplay() {
    const minutes = Math.floor(timerDuration / 60);
    const seconds = timerDuration % 60;
    document.getElementById("time-display").textContent = `Temps restant: ${minutes} min ${seconds} sec`;
}

    // معالجة انتهاء الوقت
    function handleTimeOut() {
    alert("Le temps est écoulé!");

    // إلغاء شرط "required" لجميع الحقول
    const allInputs = document.querySelectorAll("input, select, textarea");
    allInputs.forEach(input => {
    input.removeAttribute("required");
});

    // ملء الحقول الفارغة بالقيمة null
    allInputs.forEach(input => {
    if (!input.value) {
    input.value = null; // ملء الحقول الفارغة بالقيمة null
}
});

    // محاكاة الضغط على زر "Soumettre les réponses"
    submitButton.click();
}

    // بدء العداد عند تحميل الصفحة
    startTimer();
});


