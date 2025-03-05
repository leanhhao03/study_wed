<template>
    <div id="note">
        <div class="header">
            <div class="logo">logo</div>
            <div class="title">
                <a href="/">Thinksync</a>
            </div>
        </div>

        <div class="note-container">
            <div class="left-menu">
                <a v-for="btn in leftMenuButtons" :key="btn.text" :href="btn.link" :class="btn.color" class="left-menu-button">
                    <FontAwesomeIcon :icon="btn.icon" class="left-menu-icon" />
                    <span class="left-menu-text">{{ btn.text }}</span>
                </a>
            </div>

            <div class="note-grid">
                <div class="search-bar">
                    <input type="text" v-model="searchQuery" placeholder="Tạo ghi chú mới..."/>
                    <button v-for="btn in searchBarButtons" :key="btn.text" :href="btn.link" :class="btn.color" class="search-bar-button">
                        <FontAwesomeIcon :icon="btn.icon" class="search-bar-icon" />
                    </button>
                </div>

                <h2>Ghi chú gần đây</h2>

                <div class="recent-note">
                    <div class="note-group">
                        <button class="note-card">
                            <img src="" alt="Note Image" class="note-image">
                            <div class="note-info">Tên - Ngày tháng</div>
                        </button>
                        <div class="note-nav">
                            <button class="archive-btn">
                                <FontAwesomeIcon :icon="['fas', 'cloud-arrow-down']" /> Lưu trữ
                            </button>
                            <button class="trash-btn">
                                <FontAwesomeIcon :icon="['fas', 'trash']" /> Thùng rác
                            </button>
                        </div>
                    </div>

                    <div class="note-group">
                        <button class="note-card">
                            <img src="" alt="Note Image" class="note-image">
                            <div class="note-info">Tên - Ngày tháng</div>
                        </button>
                        <div class="search-bar">
                            <input type="text" v-model="searchQuery" placeholder="Tìm kiếm" />
                        </div>
                    </div>
                </div>

                <div class="main-note">
                    <div class="note-title">
                        <h4>Tên:</h4>
                        <h4>Thời gian:</h4>
                    </div>
                    <img src="" alt="img note" class="note-image">
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faBook, faPen, faCalendarAlt,faPencil, faPaintBrush, faImages, faCloudArrowDown, faTrash} from '@fortawesome/free-solid-svg-icons';

library.add(faUser, faBook, faPen, faCalendarAlt, faPencil, faPaintBrush, faImages, faCloudArrowDown, faTrash);
</script>

<script>
export default {
    components: {
        FontAwesomeIcon
    },
    data() {
        return {
            leftMenuButtons: [
                { text: "Cá nhân", icon: "user", link: "/profile" },
                { text: "Ôn tập", icon: "book", link: "/document" },
                { text: "Thi thử", icon: "pen", link: "/test" },
                { text: "Đặt lịch", icon: "calendar-alt", link: "/calendar" },
            ],
            searchBarButtons:[
                {icon: "pencil"},
                {icon: "paint-brush"},
                {icon: "images"},
            ],
            recentNotes: [
                { id: 1, title: "Tên - Ngày tháng" },
                { id: 2, title: "Tên - Ngày tháng" },
            ],
        };
    }
};
</script>