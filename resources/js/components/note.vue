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
                            <tr v-for="note in notes" :key="note.id" @click="editNote(note)">
                                <td>{{ note.title }}</td>
                                <td>{{ formatDate(note.createdAt) }}</td>
                                <td>{{ note.content }}</td>
                                <td v-if="showTrashIcons">
                                    <button @click.stop="deleteNote(note.id)" class="delete-button">
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
    { text: "Ôn tập", icon: "book", link: "/documents" },
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
const selectedNoteId = ref(null);

const editNote = (note) => {
    fetchNoteById(note.id);
};


const expandInput = () => {
    isExpanded.value = true;
};

const collapseInput = () => {
    isExpanded.value = false;
    searchQuery.value = "";
    noteContent.value = "";
    selectedNoteId.value = null;
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

const fetchNoteById = async (id) => {
    try {
        const response = await axios.get(`/api/notes/${id}`, { withCredentials: true });
        selectedNoteId.value = response.data.id;
        searchQuery.value = response.data.title;
        noteContent.value = response.data.content;
        isExpanded.value = true;
    } catch (error) {
        console.error("Lỗi khi lấy ghi chú:", error);
    }
};



const saveNote = async () => {
    if (!searchQuery.value || !noteContent.value) return;

    try {
        if (selectedNoteId.value) {
            // Gọi API cập nhật ghi chú
            await axios.put(`/api/notes/${selectedNoteId.value}`, {
                title: searchQuery.value,
                content: noteContent.value,
            }, { withCredentials: true });

            // Cập nhật danh sách
            const note = notes.value.find(n => n.id === selectedNoteId.value);
            if (note) {
                note.title = searchQuery.value;
                note.content = noteContent.value;
            }

            selectedNoteId.value = null;
        } else {
            // Gọi API tạo mới nếu không có selectedNoteId
            const response = await axios.post("/api/notes", {
                title: searchQuery.value,
                content: noteContent.value,
            }, { withCredentials: true });

            notes.value.unshift({
                id: response.data.id,
                title: response.data.title,
                content: response.data.content,
                createdAt: new Date(response.data.created_at),
            });

            recentNotes.value = notes.value.slice(0, 2);
        }

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
    background: linear-gradient(120deg, #FAD0C4 20%, #C3EBEA 80%);
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
    background-color: #edd2ff;
    padding: 5px 10px;
    cursor: pointer;
    position: relative;
    overflow: hidden; /* Đảm bảo hiệu ứng không tràn ra ngoài */
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    
}

.search-actions button::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background-color: #d28eff;
    transition: transform 0.4s ease-in-out;
    border-radius: 50%;
    z-index: 0;
    transform: translate(-50%, -50%) scale(0);
    opacity: 0.7;
    z-index: -1;
}
  
.search-actions button:hover::before {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.9;
}

.search-actions button:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    transform: translateY(-3px);
    color: #1a1a2e;
}
  
/* Hiệu ứng sóng khi nhấn */
.search-actions button:active::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120%;
    height: 120%;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    animation: ripple 0.5s ease-out;
    z-index: 1;
}

/* Hiệu ứng sáng nhẹ */
.search-actions button::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
      120deg,
      rgba(255, 255, 255, 0) 0%,
      rgba(255, 255, 255, 0.5) 50%,
      rgba(255, 255, 255, 0) 100%
    );
    transform: skewX(-20deg);
    transition: left 0.6s ease-in-out;
}

.search-actions button:hover::after {
    left: 100%;
}
@keyframes ripple {
    to {
        transform: translate(-50%, -50%) scale(2.5);
        opacity: 0;
    }
}
</style>
