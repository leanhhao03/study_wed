
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
                    <div v-for="(event, index) in events.slice(0, 9)" :key="index" class="event">
                        <span>{{ event.date }}, {{ currentMonth }}</span>
                        <span>{{ formatTime(event.time) }}</span>
                        <span>{{ event.content }}</span>
                    </div>
                </div>
            </div>

            <div v-if="!showCalendarBox" class="remind-grid">
                <div class="left-column">
                    <div class="area-of-calendar">
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
                
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBook, faPen, faBookOpen, faCalendarAlt } from '@fortawesome/free-solid-svg-icons';

library.add(faBook, faPen, faBookOpen, faCalendarAlt);

const today = new Date().getDate();
const currentMonth = new Date().toLocaleString('default', { month: 'long' });
const currentYear = new Date().getFullYear();
const selectedDate = ref(null);
const isCollapsed = ref(false);

const daysInMonth = computed(() => {
    return new Date(currentYear, new Date().getMonth() + 1, 0).getDate();
});

const previousMonthDays = computed(() => {
    const firstDay = new Date(currentYear, new Date().getMonth(), 1).getDay();
    const daysInPrevMonth = new Date(currentYear, new Date().getMonth(), 0).getDate();
    const offset = firstDay === 0 ? 6 : firstDay - 1;
    return [...Array(offset)].map((_, i) => daysInPrevMonth - offset + i + 1);
});

const nextMonthDays = computed(() => {
    const totalDisplayed = previousMonthDays.value.length + daysInMonth.value;
    const remaining = 7 - (totalDisplayed % 7);
    return remaining < 7 ? [...Array(remaining)].map((_, i) => i + 1) : [];
});

const showCalendarBox = ref(true);

const selectDate = (date) => {
    selectedDate.value = date;
    showCalendarBox.value = false;
};

const events = ref([
    { date: 11, time: "12:25", content: "Học bài" },
    { date: 18, time: "6:50", content: "Học bài" },
    { date: 8, time: "11:5", content: "Học bài" },
    { date: 18, time: "2:25", content: "Thi thử" },
    { date: 22, time: "12:25", content: "Học bài" },
]);

const toggleView = (showCalendar) => {
    showCalendarBox.value = showCalendar;
    if (showCalendar) selectedDate.value = null;
};

const showTimePicker = ref(false);
const selectedHour = ref("01");
const selectedMinute = ref("00");
const selectedPeriod = ref("AM");
const selectedTime = ref(null);
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

const formatTime = (time) => {
    let [hour, minute] = time.split(":").map(Number);
    let period = hour >= 12 ? "PM" : "AM";
    hour = hour % 12 || 12;
    return `${hour}:${minute.toString().padStart(2, '0')} ${period}`;
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

