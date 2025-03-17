<template>
    <div id="note" @click="handleClickOutside">
        <div class="header">
            <div class="logo">logo</div>
            <div class="title">
                <a href="/">Thinksync</a>
            </div>
        </div>

        <div class="note-container">
            <div class="left-menu">
                <a v-for="btn in leftMenuButtons" :key="btn.text" :href="btn.link" class="left-menu-button">
                    <FontAwesomeIcon :icon="btn.icon" class="left-menu-icon" />
                    <span class="left-menu-text">{{ btn.text }}</span>
                </a>
            </div>

            <div class="note-grid">
                <div class="add-note" ref="searchBar" :class="{ expanded: isExpanded }" @click.stop>
                    <input type="text" v-model="searchQuery" 
                        :placeholder="isExpanded ? 'Tiêu đề...' : 'Tạo ghi chú mới...'" 
                        @click="expandInput" 
                        class="search-input"
                    />
                    
                    <div v-if="!isExpanded" class="search-buttons">
                        <button class="add-note-button">
                            <FontAwesomeIcon :icon="['fas', 'plus']" class="add-note-icon" />
                        </button>
                        <button @click="toggleTrashIcons" class="add-note-button">
                            <FontAwesomeIcon :icon="['fas', 'trash']" class="add-note-icon" />
                        </button>
                    </div>

                    <textarea v-if="isExpanded" 
                        ref="textareaRef"
                        v-model="noteContent"
                        placeholder="Ghi chú..." 
                        class="search-textarea"
                        @input="autoResize">
                    </textarea>

                    <div v-if="isExpanded" class="search-actions">
                        <button @click="saveNote">Lưu</button>
                    </div>
                </div>

        
                <h2>Ghi chú gần đây</h2>
                    <div v-if="recentNotes.length === 0">❌ Không có ghi chú nào!</div>
                    <div v-else class="recent-note">
                        <div class="note-group" v-for="note in recentNotes" :key="note.id">
                            <div class="note-card">
                                <div class="note-content">
                                    <strong>{{ note.title }}</strong>
                                    <p>{{ note.content }}</p>
                                </div>
                                <div class="note-info">
                                    <p>{{ userName }}</p>
                                    <p>{{ formatDate(note.createdAt) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2>Tất cả ghi chú</h2>
                <div v-if="notes.length === 0">❌ Không có ghi chú nào!</div>
                <div v-else>
                    <table class="note-table">
                        <tbody>
                            <tr v-for="note in notes" :key="note.id">
                                <td>{{ note.title }}</td>
                                <td>{{ formatDate(note.createdAt) }}</td>
                                <td>{{ note.content }}</td>
                                <td v-if="showTrashIcons">
                                    <button @click="deleteNote(note.id)" class="delete-button">
                                        <FontAwesomeIcon :icon="['fas', 'trash']" class="trash-icon" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faBook, faPen, faCalendarAlt, faPlus, faTrash } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';

library.add(faUser, faBook, faPen, faCalendarAlt, faPlus, faTrash);

const leftMenuButtons = ref([
    { text: "Cá nhân", icon: "user", link: "/profile" },
    { text: "Ôn tập", icon: "book", link: "/document" },
    { text: "Thi thử", icon: "pen", link: "/test" },
    { text: "Đặt lịch", icon: "calendar-alt", link: "/calendar" },
]);

const searchBarButtons = ref([
    { icon: "plus" }
]);

const showTrashIcons = ref(false);
const toggleTrashIcons = () => {
    showTrashIcons.value = !showTrashIcons.value;
};

const textareaRef = ref(null);
const searchQuery = ref("");
const noteContent = ref("");
const isExpanded = ref(false);
const recentNotes = ref([]);
const userName = ref(""); 
const notes = ref([]);    

const expandInput = () => {
    isExpanded.value = true;
};

const collapseInput = () => {
    isExpanded.value = false;
    searchQuery.value = "";
    noteContent.value = "";
};
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const fetchUser = async () => {
    try {
        const response = await axios.get("/api/auth/user", { withCredentials: true });
        userName.value = response.data.name;
    } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
    }
};

const fetchNotes = async () => {
    try {
        console.log("Đang lấy danh sách ghi chú...");
        const response = await axios.get("/api/notes", { withCredentials: true });

        notes.value = response.data.map(note => ({
            id: note.id,
            title: note.title,
            content: note.content,
            createdAt: new Date(note.created_at),
        }));

        recentNotes.value = notes.value.slice(0, 2);
    } catch (error) {
        console.error("Lỗi khi lấy danh sách ghi chú:", error.response ? error.response.data : error);
    }
};


const saveNote = async () => {
    if (!searchQuery.value || !noteContent.value) return;

    try {
        const response = await axios.post("/api/notes", {
            title: searchQuery.value,
            content: noteContent.value,
        }, { withCredentials: true });

        // Cập nhật danh sách tất cả ghi chú
        notes.value.unshift({
            id: response.data.id,
            title: response.data.title,
            content: response.data.content,
            createdAt: new Date(response.data.created_at),
        });

        // Cập nhật ghi chú gần đây (2 ghi chú mới nhất)
        recentNotes.value = notes.value.slice(0, 2);

        collapseInput();
    } catch (error) {
        console.error("❌ Lỗi khi lưu ghi chú:", error);
    }
};


const deleteNote = async (id) => {
    try {
        await axios.delete(`/api/notes/${id}`, { withCredentials: true });
        recentNotes.value = recentNotes.value.filter(note => note.id !== id);
    } catch (error) {
        console.error("Lỗi khi xóa ghi chú:", error);
    }
};

const updateNote = async (id, newTitle, newContent) => {
    try {
        const response = await axios.put(`/api/notes/${id}`, {
            title: newTitle,
            content: newContent,
        }, { withCredentials: true });

        const index = recentNotes.value.findIndex(note => note.id === id);
        if (index !== -1) {
            recentNotes.value[index] = response.data;
        }
    } catch (error) {
        console.error("Lỗi khi cập nhật ghi chú:", error);
    }
};

const autoResize = () => {
    nextTick(() => {
        if (textareaRef.value) {
            textareaRef.value.style.height = "auto";
            textareaRef.value.style.height = textareaRef.value.scrollHeight + "px";
        }
    });
};

const searchBar = ref(null);
const handleClickOutside = (event) => {
    if (searchBar.value && !searchBar.value.contains(event.target)) {
        collapseInput();
    }
};

onMounted(() => {
    fetchUser();
    fetchNotes();
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.add-note {
    transition: all 0.3s ease-in-out;
    width: 500px;
    padding: 10px;
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    position: relative;
}

.add-note.expanded {
    width: 500px;
    flex-direction: column;
    align-items: stretch;
    background: white;
    padding: 15px;
    background: transparent;
}

.search-input {
    width: 100%;
    padding: 5px;
    font-size: 16px;
    border-radius: 15px;
    border: none;
}

.search-buttons {
    display: flex;
    gap: 20px;
}

.add-note-button {
    background: none;
    border: none;
    cursor: pointer;
}

.search-textarea {
    width: 100%;
    min-height: 100px;
    font-size: 14px;
    border-radius: 15px;
    outline: none;
    padding: 10px;
    resize: none;
}

.search-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

.search-actions button {
    padding: 5px 10px;
    cursor: pointer;
}
</style>
