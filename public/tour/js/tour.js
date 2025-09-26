"use strict";
// Instance the tour
var tour = new Tour({
  steps: [
  {
    element: "#dashboard",
    title: "Title of my step",
    content: "Content of my step"
  }
]});

// Initialize the tour
tour.init();

// Start the tour
tour.start();