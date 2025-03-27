<template>
  <div id="testdoc" v-if="exam">
    <div class="header">
      <div class="logo">logo</div>
      <div class="title">Thinksync</div>
    </div>

    <div class="testdoc-container">
      <div class="questions-section">
        <div v-for="(question, index) in exam.questions" :key="index" class="question-box">
          <div class="question-placeholder">Câu {{ index + 1 }}: {{ question.question }}</div>
          <div class="answer-options">
            <p>Trả lời</p>
            <ul>
              <li v-for="(option, optIndex) in question.options.slice(0, 4)" :key="optIndex" class="answer-area">
                <label>
                  <input type="radio" :name="'question' + index" :value="option" v-model="userAnswers[index]" />
                  {{ answerLabels[optIndex] }}. {{ option }}
                </label>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="test-info">
        <div class="test-info-area">
          <div class="info-header">Thông tin bài thi</div>
          <div class="info-box">
            <p>Môn thi: {{ exam.subject }}</p>
            <p>Ngày thi: {{ formatDate(testDate) }}</p>
          </div>
        </div>
        <p class="time-remaining">
          Thời gian còn lại: {{ Math.floor(timeRemaining / 60) }}:{{ ("0" + (timeRemaining % 60)).slice(-2) }}
        </p>
        <button class="submit-button" @click="submitExam">Nộp bài</button>
      </div>
    </div>
  </div>

  <div v-else>
    <p v-if="loading">Đang tải dữ liệu bài thi...</p>
    <p v-else-if="error">{{ error }}</p>
  </div>

  <div v-if="showResult" class="result-modal">
    <div class="result-box">
    <h2>KẾT QUẢ</h2>
      <div class="result-content">
        <div class="result-score">
          <p>Điểm</p>
          <p>{{ resultScore }}/{{ exam?.questions.length }}</p>
        </div>
        <div class="result-correct">
          <p>Bạn đã làm đúng</p>
          <p>{{ resultScore }}/{{ exam?.questions.length }} câu</p>
        </div>
      </div>
      <button class="btn-close" @click="closeResult">Đóng</button>
    </div>
  </div>

</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      exam: null,
      loading: true,
      error: null,
      timer: null, 
      timeRemaining: 1800, // 30 phút
      testDate: new Date(),
      answerLabels: ["A", "B", "C", "D"],
      userAnswers: {},
      user: null,      
      showResult: false,
      resultScore: 0,
    };
  },
  async created() {
    try {
      const urlParams = new URLSearchParams(window.location.search);
      const examId = urlParams.get("id");

      if (!examId || isNaN(examId)) {
        this.error = "Không tìm thấy ID bài thi!";
        this.loading = false;
        return;
      }

      // Lấy thông tin user từ session
      const { data: userData } = await axios.get("/api/auth/user");
      this.user = userData;
      if (!this.user || !this.user.id) {
        this.error = "Không tìm thấy thông tin người dùng!";
        this.loading = false;
        return;
      }

      // Gửi request để bắt đầu bài thi
      await axios.post(`/api/exams/start/${examId}`);

      // Lấy thông tin bài thi
      const response = await axios.get(`/api/exams/${examId}`);
      this.exam = response.data;

      if (typeof this.exam.questions === "string") {
        this.exam.questions = JSON.parse(this.exam.questions);
      }

      this.startTimer();
    } catch (err) {
      this.error = "Lỗi khi tải bài thi!";
      console.error(err);
    } finally {
      this.loading = false;
    }
  },
  methods: {
    startTimer() {
      this.timer = setInterval(() => {
        if (this.timeRemaining > 0) {
          this.timeRemaining--;
        } else {
          clearInterval(this.timer);
          this.autoSubmitExam();
        }
      }, 1000);
    },
    async submitExam() {
      if (!this.exam) {
        alert("Không tìm thấy bài thi!");
        return;
      }

      if (!confirm("Bạn có chắc chắn muốn nộp bài?")) return;

      if (!this.user || !this.user.id) {
        alert("Không tìm thấy ID người dùng!");
        return;
      }

      const payload = {
        answers: this.userAnswers,
      };

      try {
        const response = await axios.post(`/api/exams/${this.exam.id}/submit`, payload);
        this.resultScore = response.data.score;
        this.showResult = true;
      } catch (error) {
        console.error("Lỗi khi nộp bài:", error);
        alert("Đã có lỗi xảy ra khi nộp bài. Vui lòng thử lại.");
      }
    },
    async autoSubmitExam() {
      if (!this.exam) return;

      if (!this.user || !this.user.id) return;

      const payload = {
        answers: this.userAnswers,
      };

      try {
        await axios.post(`/api/exams/${this.exam.id}/submit`, payload);
        alert("Hết giờ! Bài thi đã được tự động nộp.");
        window.location.href = "/test";
      } catch (error) {
        console.error("Lỗi khi tự động nộp bài:", error);
      }
    },
    closeResult() {
      this.showResult = false;
      window.location.href = "/tests";
    },
    formatDate(date) {
      return date.toLocaleDateString("vi-VN", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    }
  },
  beforeUnmount() {
    clearInterval(this.timer); 
  }
};
</script>
