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
                            <h1>20</h1>                        
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
                        <label for="subject">Môn học</label>
                        <input type="text" v-model="testSubject">              
                    </div>
                    <div class="input-box">
                        <label for="question">Số lượng câu hỏi</label>
                        <input type="number" v-model="questionCount">              
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
                        
                    </div>
                    <div class="box">
                        <div class="info">
                            <p>Tiêu đề: {{ selectedDocument.documentTitle }}</p>
                            <p>Môn học: {{ selectedDocument.subject }}</p>
                            <p>Ngày đăng tải: {{ selectedDocument.uploadDate }}</p>
                            <p>Dung lượng: </p>
                        </div>
                        <div class="DetailDocButton">
                            <button class="dlt-btn">Xoá</button>
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
                        <tr v-for="(doc, index) in documents" :key="index" @click="selectedDocument = doc" class="clickable-row" :class="index % 2 === 0 ? 'even' : 'odd'">
                            <td>{{ index + 1 }}</td>
                            <td>{{ doc.uploader }}</td>
                            <td>{{ doc.id }}</td>
                            <td>{{ doc.subject }}</td>
                            <td>{{ doc.documentTitle }}</td>
                            <td>{{ doc.uploadDate }}</td>
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
                            <p>Ngày đăng tải: {{ selectedTest.uploadDate }}</p>
                            <p>Dung lượng: 
                                <input v-if="isEditingTest" type="text" v-model="selectedTest.fileSize" />
                                <span v-else>{{ selectedTest.fileSize }}</span>
                            </p>
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
                            <th>NGƯỜI ĐĂNG</th>
                            <th>ID</th>
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
                            <td>{{ test.uploader }}</td>
                            <td>{{ test.id }}</td>
                            <td>{{ test.subject }}</td>
                            <td>{{ test.questionCount }}</td>
                            <td>{{ test.time }}</td>
                            <td>{{ test.uploadDate }}</td>
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
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faMagnifyingGlass, faUser , faClipboardList, faFileAlt, faTrashCan, faPlus } from "@fortawesome/free-solid-svg-icons";
const currentModal = ref("");
const selectedDocument = ref(null);
const selectedTest = ref(null);

library.add(faMagnifyingGlass, faUser , faFileAlt, faClipboardList, faPlus, faTrashCan);

const showSearchBar = ref(false);

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

const stats = [
    { title: "Người dùng", icon: ["fas", "user"], key: "user" },
    { title: "Tài liệu", icon: ["fas", "file-alt"], key: "document" },
    { title: "Đề thi", icon: ["fas", "clipboard-list"], key: "test" },
];

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
const users = ref([
    { name: "Nguyễn Văn A", id: "0XXXXX", email: "nguyenvxx@gmail.com", gender: "Nam", birthDate: "10/10/2010" },
    { name: "Trần Thị B", id: "1YYYYY", email: "tranthib@gmail.com", gender: "Nữ", birthDate: "15/06/2012" }
]);

const documents = ref([]);

const test = ref([]);

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileName.value = file.name;
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
        if (!testSubject.value.trim() || !questionCount.value || !time.value || !fileName.value) {
            errorMessage.value = "Vui lòng nhập đủ thông tin và tải tệp lên!";
            setTimeout(() => (errorMessage.value = ""), 3000);
            return;
        }
        saveTest();
    }
    currentModal.value = "";
};

const saveDocument = () => {
    documents.value.push({
        uploader: "admin",
        id: `DOC${documents.value.length + 1}`,
        subject: subject.value,
        documentTitle: documentTitle.value,
        uploadDate: new Date().toLocaleDateString(),
    });
    documentTitle.value = "";
    subject.value = "";
    fileName.value = "";
    showModal.value = false;
};

const testSubject = ref("");
const questionCount = ref("");
const time = ref("");

const saveTest = () => {
    test.value.push({
        uploader: "admin",
        id: `TEST${test.value.length + 1}`,
        subject: testSubject.value,
        questionCount: questionCount.value,
        time: time.value,
        uploadDate: new Date().toLocaleDateString(),
        fileSize: "0 KB"
    });

    testSubject.value = "";
    questionCount.value = "";
    time.value = "";
    fileName.value = "";
};

const isEditingTest = ref(false);

const toggleEditTest = () => {
    if (isEditingTest.value) {  }
    isEditingTest.value = !isEditingTest.value;
};
</script>
