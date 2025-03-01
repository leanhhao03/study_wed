<template>
    <div id="testdoc">
        <div class="header">
            <div class="logo">logo</div>
            <div class="title">Thinksync</div>
        </div>
    </div>
    <div class="testdoc-container">
      <div class="questions-section">
        <div v-for="n in 40" :key="n" class="question-box">
          <div class="question-placeholder">Câu {{ n }}:</div>
          <div class="answer-options">
            <p>Trả lời</p>
            <ul>
              <li class="answer-area"><label><input type="radio" :name="'question' + n" value="A"> A.</label></li>
              <li class="answer-area"><label><input type="radio" :name="'question' + n" value="B"> B.</label></li>
              <li class="answer-area"><label><input type="radio" :name="'question' + n" value="C"> C.</label></li>
              <li class="answer-area"><label><input type="radio" :name="'question' + n" value="D"> D.</label></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="test-info">
        <div class="test-info-area">
            <div class="info-header">Ôn tập</div>
            <div class="info-box">
                <p>Môn thi:</p>
                <p>Ngày thi:</p>
            </div>
        </div>
        <p class="time-remaining">Thời gian còn lại:</p>
        <input type="submit" class="submit-button" value="Nộp bài">
      </div>
    </div>
  </template>