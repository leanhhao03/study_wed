<template>
    <div id="calendar">
        <div class="header">
            <div class="logo">logo</div>
            <div class="title">
                <a href="/">Thinksync</a>
            </div>
        </div>
        
        <div class="calendar-container">
            <div class="left-menu">
                <a v-for="btn in leftMenuButtons" :key="btn.text" :href="btn.link" :class="btn.color" class="left-menu-button">
                    <FontAwesomeIcon :icon="btn.icon" class="left-menu-icon" />
                    <span class="left-menu-text">{{ btn.text }}</span>
                </a>
            </div>
            <div v-if="showCalendarBox" class="calendar-box">
                <div class="calendar-header">
                    <h2>{{ currentMonth }} {{ currentYear }}</h2>
                </div>
                <div class="calendar-grid">
                    <div class="day" v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']" :key="day">{{ day }}</div>

                    <div v-for="date in previousMonthDays" :key="'prev-' + date" class="date disabled">{{ date }}</div>
                    
                    <div v-for="date in daysInMonth" :key="date" 
                        :class="['date', { 'selected': date === today, 'manual-selected': date === selectedDate }]"
                        @click="selectDate(date)">
                        {{ date }}
                    </div>

                    <div v-for="date in nextMonthDays" :key="'next-' + date" class="date disabled">{{ date }}</div>
                </div>

                <div v-if="!isCollapsed" class="schedule-box">
                    <div v-for="(event, index) in events" :key="index" class="event">
                        <span>{{ event.date }}, {{ event.month }}</span>
                        <span>{{ event.time }}</span>
                        <span>{{ event.content }}</span>
                    </div>
                </div>
            </div>

            <div v-if="!showCalendarBox" class="remind-grid">
                <div class="left-column">
                    <div class="area-of-calendar">
                        <div class="calendar-header">
                            <h2>{{ currentMonth }} {{ currentYear }}</h2>
                            <div class="month-nav">
                                <button @click="previousMonth" :disabled="isCurrentMonth" :class="{ 'disabled': isCurrentMonth }">
                                    <FontAwesomeIcon :icon="['fas', 'arrow-left']" />
                                </button>
                                <button @click="nextMonth">
                                    <FontAwesomeIcon :icon="['fas', 'arrow-right']" />
                                </button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <div class="day" v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']" :key="day">{{ day }}</div>
                            <div v-for="date in previousMonthDays" :key="'prev-' + date" class="date disabled">{{ date }}</div>
                            <div v-for="date in daysInMonth" :key="date" 
                                :class="['date', { 'selected': date === today, 'manual-selected': date === selectedDate }]"
                                @click="selectDate(date)">
                                {{ date }}
                            </div>
                            <div v-for="date in nextMonthDays" :key="'next-' + date" class="date disabled">{{ date }}</div>
                        </div>
                    </div>
                    <div v-if="showTimePicker" class="time-picker-overlay">
                        <div class="time-picker">
                            <div class="time-columns">
                                <ul class="time-list">
                                    <li v-for="h in 12" :key="h" @click="selectHour(h)" :class="{ 'hover': selectedHour === h }">{{ String(h).padStart(2, '0') }}</li>
                                </ul>
                            </div>
                            <div class="time-columns">
                                <ul class="time-list">
                                    <li v-for="m in minuteOptions" :key="m" @click="selectMinute(m)" :class="{ 'hover': selectedMinute === m }">
                                        {{ String(m).padStart(2, '0') }}
                                    </li>
                                </ul>
                            </div>
                            <div class="time-columns">
                                <ul class="time-list">
                                    <li @click="selectPeriod('AM')" :class="{ 'hover': selectedPeriod === 'AM' }">AM</li>
                                    <li @click="selectPeriod('PM')" :class="{ 'hover': selectedPeriod === 'PM' }">PM</li>
                                </ul>
                            </div>
                        </div>
                        <button class="save-time-btn" @click="saveTime">Tạo</button>
                    </div>
                </div>
                <div class="area-of-remind">
                    <div class="remind-btn">
                        <button @click="createReminder">Tạo nhắc hẹn</button>
                        <button @click="toggleView(true)">Danh sách nhắc hẹn</button>
                    </div>
                    <div class="content-box">
                        <div class="content-title">
                            <input v-model="reminderTitle" type="text" placeholder="Tiêu đề">
                        </div>
                        <div class="content-area">
                            <textarea v-model="reminderContent" placeholder="Nội dung"></textarea>
                        </div>
                        <div class="time-remind">
                            <button class="time-setting-btn" @click="toggleTimePicker">Thời gian</button>
                            <div class="time-showing">{{ selectedTime || "Thời gian" }}</div>
                        </div>
                    </div>
                </div>
                
                <div v-if="errorMessage" class="calendar-error-message">{{ errorMessage }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBook, faPen, faBookOpen, faCalendarAlt, faArrowLeft, faArrowRight } from '@fortawesome/free-solid-svg-icons';

library.add(faBook, faPen, faBookOpen, faCalendarAlt, faArrowLeft, faArrowRight);

const today = new Date().getDate();
const currentMonthIndex = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());
const selectedDate = ref(null);
const isCollapsed = ref(false);
const reminderTitle = ref('');
const reminderContent = ref('');
const selectedHour = ref("01");
const selectedMinute = ref("00");
const selectedPeriod = ref("AM");
const selectedTime = ref(null);
const showTimePicker = ref(false);
const errorMessage = ref('');

const currentMonth = computed(() => new Date(currentYear.value, currentMonthIndex.value).toLocaleString('default', { month: 'long' }));

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonthIndex.value + 1, 0).getDate();
});

const previousMonthDays = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonthIndex.value, 1).getDay();
    const daysInPrevMonth = new Date(currentYear.value, currentMonthIndex.value, 0).getDate();
    const offset = firstDay === 0 ? 6 : firstDay - 1;
    return [...Array(offset)].map((_, i) => daysInPrevMonth - offset + i + 1);
});

const nextMonthDays = computed(() => {
    const totalDisplayed = previousMonthDays.value.length + daysInMonth.value;
    const remaining = (6 * 7) - totalDisplayed; // Đảm bảo đủ 6 hàng
    return remaining > 0 ? [...Array(remaining)].map((_, i) => i + 1) : [];
});
;

const showCalendarBox = ref(true);
const events = ref([]);

const selectDate = (date) => {
    selectedDate.value = date;
    showCalendarBox.value = false;
};

const toggleView = (showCalendar) => {
    showCalendarBox.value = showCalendar;
};

const isCurrentMonth = computed(() => {
    const now = new Date();
    return currentYear.value === now.getFullYear() && currentMonthIndex.value === now.getMonth();
});

const nextMonth = () => {
    if (currentMonthIndex.value < 11) {
        currentMonthIndex.value++;
    } else {
        currentMonthIndex.value = 0;
        currentYear.value++;
    }
};

const previousMonth = () => {
    if (!isCurrentMonth.value) {
        if (currentMonthIndex.value > 0) {
            currentMonthIndex.value--;
        } else {
            currentMonthIndex.value = 11;
            currentYear.value--;
        }
    }
};

const minuteOptions = computed(() => {
    return Array.from({ length: 12 }, (_, i) => i * 5);
});

const selectHour = (hour) => {
    selectedHour.value = hour;
};

const selectMinute = (minute) => {
    selectedMinute.value = minute;
};

const selectPeriod = (period) => {
    selectedPeriod.value = period;
};

const saveTime = () => {
    const formattedHour = String(selectedHour.value).padStart(2, '0');
    const formattedMinute = String(selectedMinute.value).padStart(2, '0');
    selectedTime.value = `${formattedHour}:${formattedMinute} ${selectedPeriod.value}`;
    showTimePicker.value = false;
};

const toggleTimePicker = () => {
    showTimePicker.value = !showTimePicker.value;
};

// Hàm tạo nhắc hẹn
const createReminder = () => {
    const selectedFullDate = new Date(currentYear.value, currentMonthIndex.value, selectedDate.value);
    const todayFullDate = new Date();
    todayFullDate.setHours(0, 0, 0, 0);

    if (selectedFullDate < todayFullDate) {
        errorMessage.value = "Ngày đã chọn không hợp lệ.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }

    if (!selectedDate.value) {
        errorMessage.value = "Vui lòng chọn ngày.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }
    if (!reminderTitle.value) {
        errorMessage.value = "Vui lòng nhập tiêu đề.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }
    if (!reminderContent.value) {
        errorMessage.value = "Vui lòng nhập nội dung.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }
    if (!selectedTime.value) {
        errorMessage.value = "Vui lòng chọn thời gian.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }
    const now = new Date();
    const [hour, minute, period] = selectedTime.value.split(/[: ]/);
    let selectedHour24 = parseInt(hour);
    if (period === "PM" && selectedHour24 !== 12) selectedHour24 += 12;
    if (period === "AM" && selectedHour24 === 12) selectedHour24 = 0;
    
    const selectedDateTime = new Date(currentYear.value, currentMonthIndex.value, selectedDate.value, selectedHour24, parseInt(minute));
    
    if (selectedDateTime - now < 30 * 60 * 1000) {
        errorMessage.value = "Vui lòng chọn thời gian cách hiện tại 30'.";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }

    events.value.push({
        date: selectedDate.value,
        month: currentMonth.value,
        time: selectedTime.value,
        content: reminderContent.value,
    });

    // Reset các biến về mặc định
    selectedDate.value = null;
    reminderTitle.value = "";
    reminderContent.value = "";
    selectedHour.value = "01";
    selectedMinute.value = "00";
    selectedPeriod.value = "AM";
    selectedTime.value = null;

    showCalendarBox.value = true;
};

</script>

<script>
export default {
    components: {
        FontAwesomeIcon
    },
    data() {
        return {
            leftMenuButtons: [
                { text: "Ôn tập", icon: "book", link: "/document" },
                { text: "Thi thử", icon: "pen", link: "/test" },
                { text: "Ghi nhớ", icon: "book-open", link: "/note" },
                { text: "Đặt lịch", icon: "calendar-alt", link: "/calendar" },
            ]
        };
    }
};
</script>

