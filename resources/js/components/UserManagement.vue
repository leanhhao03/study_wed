<template>
    <div id="UserManagement">
        <div class="header">
            <div class="header-box">
                <div class="page-name">
            </div>
            <div class="title">Thinksync</div>
            </div>
            <div class="admin">
                <div class="ad-search-box" :class="{ active: showSearchBar }">
                    <input type="text" placeholder="Tìm kiếm..." v-show="showSearchBar" />
                    <FontAwesomeIcon 
                        :icon="['fas', 'magnifying-glass']" 
                        class="admin-icon" 
                        @click="toggleSearchBar" 
                    />
                </div>
                <h3>Admin</h3>
            </div>
        </div>

        <div class="User-Management-container">
            <!-- Dashboard -->
            <div class="ad-dashboard"> 
                <div class="dashboard-item">
                    <div v-for="(item, index) in stats" :key="index" class="das-card-item"@click="currentTable = item.key":class="{ active: currentTable === item.key }">
                        <div><font-awesome-icon :icon="item.icon" class="icon" /></div>
                        <div class="card-content">
                            <h2>{{ item.title }}</h2>
                            <h1></h1>                        
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button class="add-btn" @click="openModal">
                        <font-awesome-icon :icon="['fas', 'plus']" class="add-icon"/> THÊM
                    </button>
                    <button class="delete-btn" @click="toggleDeleteIcons">
                        <font-awesome-icon :icon="['fas', 'trash-can']" class="dlt-icon" /> XÓA
                    </button>
                </div>
            </div>

            <!-- Form Tải Lên Tài Liệu -->
            <div v-if="currentModal === 'document'" class="modal" @click.self="currentModal = ''">
                <div class="EditDocumentForm">
                    <h2>Tải lên tài liệu</h2>
                    <div class="input-box">
                        <label for="title-doc">Tiêu đề tài liệu</label>
                        <input type="text" v-model="documentTitle">
                    </div>
                    <div class="input-box">
                        <label for="subject">Môn học</label>
                        <input type="text" v-model="subject">              
                    </div>
                    <div class="file-name-showing">{{ fileName || "*file của tài liệu*" }}</div>
                    <div class="modal-btn">
                        <label class="upload-btn">
                            <input type="file" @change="onFileChange" hidden>
                            {{ fileName ? 'Sửa tài liệu' : 'Thêm tài liệu' }}
                        </label>
                        <button @click="validateAndSave">Lưu</button>
                    </div>
                </div>
            </div>

            <!-- Form Tải Lên Đề Thi -->
            <div v-if="currentModal === 'test'" class="modal" @click.self="currentModal = ''">
                <div class="EditTestForm">
                    <h2>Tải lên đề thi</h2>
                    <div class="input-box">
                        <label for="title">Tiêu đề</label>
                        <input type="text" v-model="testTitle">              
                    </div>
                    <div class="input-box">
                        <label for="subject">Môn học</label>
                        <input type="text" v-model="testSubject">              
                    </div>
                    <div class="input-box">
                        <label for="time">Thời gian (phút)</label>
                        <input type="number" v-model="time">              
                    </div>
                    <div class="file-name-showing">{{ fileName || "*file của đề thi*" }}</div>
                    <div class="modal-btn">
                        <label class="upload-btn">
                            <input type="file" @change="onFileChange" hidden>
                            {{ fileName ? 'Sửa đề thi' : 'Thêm đề thi' }}
                        </label>
                        <button @click="validateAndSave">Lưu</button>
                    </div>
                </div>
            </div>

            <!-- Bảng Người Dùng -->
            <div v-if="currentTable === 'user'" class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>SỐ TT</th>
                            <th>TÊN NGƯỜI DÙNG</th>
                            <th>ID</th>
                            <th>GMAIL</th>
                            <th>GIỚI TÍNH</th>
                            <th>NGÀY SINH</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index" :class="index % 2 === 0 ? 'even' : 'odd'">
                            <td>{{ index + 1 }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.id }}</td>
                            <td><a href="mailto:{{ user.email }}">{{ user.email }}</a></td>
                            <td>{{ user.gender }}</td>
                            <td>{{ user.birthDate }}</td>
                            <td>
                                <FontAwesomeIcon 
                                    :icon="['fas', 'trash-can']" 
                                    class="delete-icon" 
                                    :class="{ hidden: !showDeleteIcons }" 
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="selectedDocument" class="detail-container" @click.self="selectedDocument = null">
                <div class="detail-area">
                    <div class="viewer">
                        <iframe v-if="selectedPdf" 
                        :src="`${selectedPdf}#toolbar=0`"

                        frameborder="0"></iframe>
                    </div>
                    <div class="box">
                        <div class="info">
                            <p>Tiêu đề: {{ selectedDocument.title }}</p>
                            <p>Môn học: {{ selectedDocument.subject }}</p>
                            <p>Ngày đăng tải: {{ selectedDocument.created_at }}</p>
                        </div>
                        <div class="DetailDocButton">
                            <button class="dlt-btn" @click="deleteDocument(selectedDocument.id)">Xoá</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bảng tài liệu -->
            <div v-if="currentTable === 'document'" class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>SỐ TT</th>
                            <th>NGƯỜI ĐĂNG</th>
                            <th>ID</th>
                            <th>MÔN HỌC</th>
                            <th>TIÊU ĐỀ</th>
                            <th>NGÀY ĐĂNG TẢI</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                   <tr v-for="(doc, index) in documents" :key="index" @click="selectDocument(doc)" class="clickable-row" :class="index % 2 === 0 ? 'even' : 'odd'">
                        <td>{{ index + 1 }}</td>
                        <td>{{ doc.user.name }}</td>
                        <td>{{ doc.id }}</td>
                        <td>{{ doc.subject }}</td>
                        <td>{{ doc.title }}</td>
                        <td>{{ doc.created_at }}</td>
                        <td>
                            <FontAwesomeIcon 
                                :icon="['fas', 'trash-can']" 
                                class="delete-icon" 
                                :class="{ hidden: !showDeleteIcons }" 
                            />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="selectedTest" class="detail-container" @click.self="selectedTest = null">
                <div class="detail-area">
                    <div class="viewer">
                        <!-- Thêm nội dung hiển thị đề thi ở đây -->
                    </div>
                    <div class="box">
                        <div class="info">
                            <p>Môn học: 
                                <input v-if="isEditingTest" type="text" v-model="selectedTest.subject"/>
                                <span v-else>{{ selectedTest.subject }}</span>
                            </p>
                            <p>Ngày đăng tải: {{ selectedTest.created_at }}</p>
                        </div>
                        <div class="DetailTestButton">
                            <button class="edit-btn" @click="toggleEditTest">{{ isEditingTest ? 'Lưu' : 'Sửa' }}</button>
                            <button class="dlt-btn">Xoá</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bảng Đề Thi -->
            <div v-if="currentTable === 'test'" class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>SỐ TT</th>
                            <th>ID</th>
                            <th>Tiêu Đề</th>
                            <th>MÔN HỌC</th>
                            <th>SỐ LƯỢNG CÂU HỎI</th>
                            <th>THỜI GIAN</th>
                            <th>NGÀY ĐĂNG TẢI</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(test, index) in test" :key="index" @click="selectedTest = test" class="clickable-row" :class="index % 2 === 0 ? 'even' : 'odd'">
                            <td>{{ index + 1 }}</td>
                            <td>{{ test.id }}</td>
                            <td>{{ test.title }}</td>
                            <td>{{ test.subject }}</td>
                            <td>{{ test.total_questions }}</td>
                            <td>{{ test.duration }}</td>
                            <td>{{ test.created_at }}</td>
                            <td>
                                <FontAwesomeIcon 
                                    :icon="['fas', 'trash-can']" 
                                    class="delete-icon" 
                                    :class="{ hidden: !showDeleteIcons }" 
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

    </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faMagnifyingGlass, faUser , faClipboardList, faFileAlt, faTrashCan, faPlus } from "@fortawesome/free-solid-svg-icons";

library.add(faMagnifyingGlass, faUser , faFileAlt, faClipboardList, faPlus, faTrashCan);
const stats = [
    { title: "Người dùng", icon: ["fas", "user"], key: "user" },
    { title: "Tài liệu", icon: ["fas", "file-alt"], key: "document" },
    { title: "Đề thi", icon: ["fas", "clipboard-list"], key: "test" },
];

const currentModal = ref("");
const selectedDocument = ref(null);
const selectedTest = ref(null);
const showSearchBar = ref(false);
const selectedPdf = ref("");

const toggleSearchBar = () => {
    if (currentTable.value === 'document' || currentTable.value === 'test') {
        showSearchBar.value = !showSearchBar.value; 
    }
};

document.addEventListener("click", function (event) {
    const searchContainer = document.querySelector(".admin .ad-search-box");
    const searchIcon = document.querySelector(".admin-icon");

    if (!searchContainer.contains(event.target) && !searchIcon.contains(event.target)) {
        showSearchBar.value = false;
    }
});


const openModal = () => {
    if (currentTable.value === "document") {
        currentModal.value = "document";
    } else if (currentTable.value === "test") {
        currentModal.value = "test";
    } else {
        currentModal.value = "";
    }
};

const showDeleteIcons = ref(false);

const toggleDeleteIcons = () => {
    showDeleteIcons.value = !showDeleteIcons.value;
};

const currentTable = ref("user");
const showModal = ref(false);
const documentTitle = ref("");
const subject = ref("");
const fileName = ref("");
const users = ref([]);

const fetchUsers = async () => {
    try {
        const response = await axios.get("/api/auth/fecthUser");
        users.value = response.data;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách người dùng:", error);
    }
};
const documents = ref([]);
const fetchDoc = async () => {
    try {
        const response = await axios.get("/api/documents/getFile");
        documents.value = response.data;
        console.log(response.data)
    } catch (error) {
        console.error("Lỗi khi lấy danh sách tài liệu:", error);
    }
};

const test = ref([]);
const fetchTest = async () => {
    try {
        const response = await axios.get("/api/exams");
        test.value = response.data;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách Đề thi:", error);
    }
};

const onFileChange = (event) => {
    file.value = event.target.files[0];
    if (file.value) {
        fileName.value = file.value.name;
    }
};

const errorMessage = ref("");

const validateAndSave = () => {
    if (currentModal.value === "document") {
        if (!documentTitle.value.trim() || !subject.value.trim() || !fileName.value) {
            errorMessage.value = "Vui lòng nhập đủ thông tin và tải tệp lên!";
            setTimeout(() => (errorMessage.value = ""), 3000);
            return;
        }
        saveDocument();
    } else if (currentModal.value === "test") {
        if (!testSubject.value.trim() || !time.value || !fileName.value) {
            errorMessage.value = "Vui lòng nhập đủ thông tin và tải tệp lên!";
            setTimeout(() => (errorMessage.value = ""), 3000);
            return;
        }
        saveTest();
    }
    currentModal.value = "";
};

const saveDocument = async () => {
    if (!file.value) {
        errorMessage.value = "Vui lòng chọn file!";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }

    const formData = new FormData();
    formData.append("file", file.value);
    formData.append("subject", subject.value);
    formData.append("title_file", documentTitle.value);

    try {
        const response = await axios.post("/api/documents/upload", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.data.file) {
            documents.value.push(response.data.file);
        }

        // Reset form
        documentTitle.value = "";
        subject.value = "";
        fileName.value = "";
        file.value = null;
        currentModal.value = "";
    } catch (error) {
        console.error("Lỗi khi tải file lên:", error);
        errorMessage.value = "Có lỗi xảy ra khi tải file!";
        setTimeout(() => (errorMessage.value = ""), 3000);
    }
};

const file = ref(null)
const testSubject = ref("");
const testTitle = ref("");
const time = ref("");

const saveTest = async () => {
    if (!file.value) {
        errorMessage.value = "Vui lòng chọn file!";
        setTimeout(() => (errorMessage.value = ""), 3000);
        return;
    }

    const formData = new FormData();
    formData.append("file", file.value);
    formData.append("subject", testSubject.value);
    formData.append("title", testTitle.value);
    formData.append("duration", time.value);

    try {
        const response = await axios.post("/api/exams/upload", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.data.test) {
            test.value.push(response.data.test); 
        }

        // Reset form
        testTitle.value = "";
        testSubject.value = "";
        time.value = "";
        fileName.value = "";
        file.value = null;
        currentModal.value = "";
    } catch (error) {
        console.error("Lỗi khi tải đề thi lên:", error);
        errorMessage.value = "Có lỗi xảy ra khi tải đề thi!";
        setTimeout(() => (errorMessage.value = ""), 3000);
    }
};

const isEditingTest = ref(false);

const toggleEditTest = () => {
    if (isEditingTest.value) {  }
    isEditingTest.value = !isEditingTest.value;
};

const deleteDocument = async (id) => {
    if (!confirm("Bạn có chắc chắn muốn xóa tài liệu này?")) return;

    try {
        await axios.delete(`/api/documents/${id}`);
        documents.value = documents.value.filter(doc => doc.id !== id);
    } catch (error) {
        console.error("Lỗi khi xóa tài liệu:", error);
        errorMessage.value = "Không thể xóa tài liệu!";
        setTimeout(() => (errorMessage.value = ""), 3000);
    }
};
const getFullPdf = async (id) => {
    try {
        const response = await axios.get(`/api/documents/full/${id}`);
        selectedPdf.value = response.data.file_url;
    } catch (error) {
        console.error("Lỗi khi mở tài liệu:", error);
    }
};
const selectDocument = async (doc) => {
    selectedDocument.value = doc;
    await getFullPdf(doc.id);
};


onMounted(() => {
    fetchUsers();
    fetchDoc();
    fetchTest();
});
</script>
