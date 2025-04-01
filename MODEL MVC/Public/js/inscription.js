function showTab(tabId, event) {
  const tabs = document.querySelectorAll(".tab-content");
  tabs.forEach(tab => tab.style.display = "none");

  const buttons = document.querySelectorAll(".tab-button");
  buttons.forEach(button => button.classList.remove("active"));

  document.getElementById(tabId).style.display = "block";

  if (event) {
    event.currentTarget.classList.add("active");
  }
}