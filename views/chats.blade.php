@extends ("layouts/app")
@section("title", "Chats")

@section ("main")

    <div id="chat-app"></div>

    <input type="hidden" id="user-id" value="{{ $id }}" />

    <script type="text/babel">
        function Chat() {

            const userId = parseInt(document.getElementById("user-id").value || "0");

            const [users, setUsers] = React.useState([]);
            const [search, setSearch] = React.useState("");
            const [selectedUser, setSelectedUser] = React.useState(null); 
            const [messages, setMessages] = React.useState([]);
            const [messageInput, setMessageInput] = React.useState("");
            const [loading, setLoading] = React.useState(false);
            const [loadingMessages, setLoadingMessages] = React.useState(false);
            const [refetchUsers, setRefetchUsers] = React.useState(1);
            const [refetchMessages, setRefetchMessages] = React.useState(1);
            const [messagesPage, setMessagesPage] = React.useState(1);
            const [page, setPage] = React.useState(1);
            const [sendingMessage, setSendingMessage] = React.useState(false);
            const [timer, setTimer] = React.useState(null);
            const [selectedFiles, setSelectedFiles] = React.useState([]);

            const handleSelectUser = (user) => {
                setSelectedUser(user);
            };

            const handleSendMessage = async function () {
                if (!messageInput.trim()) return;

                if (selectedUser == null) {
                    alert("Please select a user first.");
                    return;
                }
                
                setSendingMessage(true);

                try {
                    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    const formData = new FormData();
                    formData.append("time_zone", timeZone);
                    formData.append("user_id", selectedUser.user_id);
                    formData.append("message", messageInput);

                    selectedFiles.map(function (selectedFile) {
                        formData.append("attachments[]", selectedFile);
                    });
                   
                    const response = await axios.post(
                        baseUrl + "/messages/send",
                        formData,
                        {
                            headers: {
                                Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                            }
                        }
                    );

                    if (response.data.status == "success") {
                        const messageObj = response.data.message_obj;
                        const msgs = [ ...messages ];
                        msgs.push(messageObj);
                        setMessages(msgs);
                        setMessageInput("");
                        setSelectedFiles([]);
                    } else {
                        swal.fire("Error", response.data.message, "error");
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    setSendingMessage(false);
                }
            };

            async function fetch() {
                setLoading(true);

                try {
                    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    const formData = new FormData();
                    formData.append("time_zone", timeZone);
                    formData.append("search", search);
                    formData.append("id", userId);
                   
                    const response = await axios.post(
                        baseUrl + "/messages/fetch-users",
                        formData,
                        {
                            headers: {
                                Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                            }
                        }
                    );

                    if (response.data.status == "success") {
                        setUsers(response.data.users);
                    } else {
                        swal.fire("Error", response.data.message, response.data.status);
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    setLoading(false);
                }
            }

            async function fetchMessages() {
                if (selectedUser == null) {
                    alert("Please select a user first.");
                    return;
                }

                setLoadingMessages(true);

                try {
                    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    const formData = new FormData();
                    formData.append("time_zone", timeZone);
                    formData.append("user_id", selectedUser.user_id);
                    formData.append("page", messagesPage);
                   
                    const response = await axios.post(
                        baseUrl + "/messages/fetch",
                        formData,
                        {
                            headers: {
                                Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                            }
                        }
                    );

                    if (response.data.status == "success") {
                        setMessages(response.data.messages);

                        setTimeout(function () {
                            if (messagesPage == 1) {
                                const messagesRef = document.querySelector(".chat-body ul");
                                if (messagesRef.current) {
                                    messagesRef.current.scrollTop = messagesRef.current.scrollHeight;
                                }
                            }
                        }, 500);

                        let unreadCountReduced = 0;
                        for (let a = 0; a < users.length; a++) {
                            if (users[a].id == selectedUser.id) {
                                unreadCountReduced = users[a].unread_count;
                                users[a].unread_count = 0;
                                break;
                            }
                        }
                        setUsers(users);
                    } else {
                        swal.fire("Error", response.data.message, "error");
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    setLoadingMessages(false);
                }
            }

            React.useEffect(function () {
                if (selectedUser != null) {
                    fetchMessages();
                }
            }, [selectedUser, refetchMessages]);

            React.useEffect(function () {
                fetch();
            }, [refetchUsers]);

            const styles = {
                unreadCount: {
                    position: "absolute",
                    right: "10px"
                },
                profileImage: {
                    width: "50px",
                    height: "50px",
                    objectFit: "cover",
                    borderRadius: "50%",
                    marginRight: "10px"
                }
            };

            return (
                <>
                    <div className="content">
                        <div className="container-fluid">
                            <div className="row">
                                <div className="col-xl-12">
                                    <div className="chat-window">
                                    
                                        <div className="chat-cont-left">
                                            <div className="chat-header">
                                                <span>Chats</span>
                                            </div>
                                            <form className="chat-search">
                                                <div className="input-group">
                                                    <div className="input-group-prepend">
                                                        <i className="fas fa-search"></i>
                                                    </div>
                                                    <input type="text" className="form-control" placeholder="Search"
                                                        value={ search }
                                                        onChange={ function (e) {
                                                            setSearch(e.target.value);

                                                            clearTimeout(timer);

                                                            setTimer(setTimeout(function () {
                                                                setRefetchUsers(refetchUsers + 1);
                                                            }, 500));
                                                        } } />
                                                </div>
                                            </form>
                                            <div className="chat-users-list">
                                                <div className="chat-scroll">

                                                    { users.map(function (u, index) {
                                                        return (
                                                            <a href="#" className={ `media user ${ u.id === selectedUser?.id ? 'active' : '' }` }
                                                                onClick={ function (event) {
                                                                    event.preventDefault();
                                                                    handleSelectUser(u);
                                                                } }
                                                                key={ `contact-${ u.id }` }>
                                                                <div className="media-img-wrap">
                                                                    <div className="avatar">
                                                                        <img src={ u.profile_image }
                                                                            onError={ function (event) {
                                                                                event.target.src = baseUrl + "/img/user-placeholder.png";
                                                                            } }
                                                                            alt={ u.name }
                                                                            className="avatar-img rounded-circle" />
                                                                    </div>
                                                                </div>
                                                                <div className="media-body">
                                                                    <div>
                                                                        <div className="user-name contact-name">{ u.name }</div>
                                                                    </div>

                                                                    { u.unread_count > 0 && (
                                                                        <div>
                                                                            <div className="badge badge-success badge-pill">{ u.unread_count }</div>
                                                                        </div>
                                                                    ) }
                                                                </div>
                                                            </a>
                                                        );
                                                    }) }

                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div className="chat-cont-right">
                                            { selectedUser != null && (
                                                <>
                                                    <div className="chat-header">
                                                        <a id="back_user_list" href="#" className="back-user-list"
                                                            onClick={ function (event) {
                                                                event.preventDefault();
                                                            } }>
                                                            <i className="material-icons">chevron_left</i>
                                                        </a>
                                                        <div className="media">
                                                            <div className="media-img-wrap">
                                                                <div className="avatar">
                                                                    <img src={ selectedUser.profile_image }
                                                                        alt={ selectedUser.name }
                                                                        onError={ function (event) {
                                                                            event.target.src = baseUrl + "/img/user-placeholder.png";
                                                                        } }
                                                                        className="avatar-img rounded-circle" />
                                                                </div>
                                                            </div>
                                                            <div className="media-body">
                                                                <div className="user-name">{ selectedUser.name }</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="chat-body">
                                                        <div className="chat-scroll">
                                                            <ul className="list-unstyled">

                                                                { messages.map((msg, index) => (
                                                                    <li key={ `message-${ index }` }
                                                                        className={`media ${ msg.sender_id == user?.id ? "sent" : "received" }`}>
                                                                        <div className="media-body">
                                                                            <div className="msg-box">
                                                                                <div>
                                                                                    <p>{ msg.message }</p>
                                                                                    <ul className="chat-msg-info">
                                                                                        <li>
                                                                                            <div className="chat-time">
                                                                                                <span>{ msg.created_at }</span>
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>

                                                                            { msg.attachments.length > 0 && (
                                                                                <div className="msg-box">
                                                                                    <div>

                                                                                        <div className="chat-msg-attachments">
                                                                                            { msg.attachments.map(function (attachment, attachmentIndex) {
                                                                                                return (
                                                                                                    <div className="chat-attachment"
                                                                                                        key={ `attachment-index-${ msg.id }-${ attachmentIndex }` }>
                                                                                                        
                                                                                                        <a href={ `${ baseUrl }/messages/buffer-attachment/${ attachment.id }` }
                                                                                                            target="_blank">
                                                                                                            { attachment.name }
                                                                                                        </a>
                                                                                                    </div>
                                                                                                );
                                                                                            }) }
                                                                                        </div>

                                                                                        <ul className="chat-msg-info">
                                                                                            <li>
                                                                                                <div className="chat-time">
                                                                                                    <span>8:41 AM</span>
                                                                                                </div>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            ) }
                                                                        </div>
                                                                    </li>
                                                                )) }

                                                                {/*<li className="media sent">
                                                                    <div className="media-body">
                                                                        <div className="msg-box">
                                                                            <div>
                                                                                <p>Hello. What can I do for you?</p>
                                                                                <ul className="chat-msg-info">
                                                                                    <li>
                                                                                        <div className="chat-time">
                                                                                            <span>8:30 AM</span>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                <li className="media received">
                                                                    <div className="avatar">
                                                                        <img src="assets/img/doctors/doctor-thumb-02.jpg"
                                                                            alt="User Image"
                                                                            className="avatar-img rounded-circle" />
                                                                    </div>
                                                                    <div className="media-body">
                                                                        <div className="msg-box">
                                                                            <div>
                                                                                <p>I'm just looking around.</p>
                                                                                <p>Will you tell me something about yourself?</p>
                                                                                <ul className="chat-msg-info">
                                                                                    <li>
                                                                                        <div className="chat-time">
                                                                                            <span>8:35 AM</span>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div className="msg-box">
                                                                            <div>
                                                                                <div className="chat-msg-attachments">

                                                                                    <div className="chat-attachment">
                                                                                        <img src="assets/img/img-02.jpg" alt="Attachment" />
                                                                                        <div className="chat-attach-caption">placeholder.jpg</div>
                                                                                        <a href="#" className="chat-attach-download"
                                                                                            onClick={ function (event) {
                                                                                                event.preventDefault();
                                                                                            } }>
                                                                                            <i className="fas fa-download"></i>
                                                                                        </a>
                                                                                    </div>

                                                                                </div>
                                                                                <ul className="chat-msg-info">
                                                                                    <li>
                                                                                        <div className="chat-time">
                                                                                            <span>8:41 AM</span>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>*/}
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div className="chat-footer">
                                                        <form encType="multipart/form-data" id="form-send"
                                                            onSubmit={ function (event) {
                                                                event.preventDefault();
                                                                handleSendMessage();
                                                            } }
                                                            className="display-contents">
                                                            <div className="input-group">
                                                                <div className="input-group-prepend">
                                                                    <div className="btn-file btn">
                                                                        <i className="fa fa-paperclip"></i>
                                                                        <input type="file" multiple
                                                                            accept="image/*, video/*, .pdf, .doc,. docx"
                                                                            onChange={ function (event) {
                                                                                setSelectedFiles(Array.from(event.target.files));
                                                                            } } />
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                    className="input-msg-send form-control"
                                                                    placeholder="Type something"
                                                                    value={ messageInput }
                                                                    onChange={(e) => setMessageInput(e.target.value)} />
                                                                <div className="input-group-append">
                                                                    <button type="submit"
                                                                        className="btn msg-send-btn"
                                                                        disabled={ sendingMessage }>
                                                                        <i className="fab fa-telegram-plane"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            { selectedFiles.map(function (selectedFile, index) {
                                                                return (
                                                                    <div key={ `selectedFile-${ index }` }
                                                                        className="mt-3">
                                                                        { selectedFile.name }&nbsp;
                                                             
                                                                        <button type="button"
                                                                            onClick={ function () {
                                                                                const temp = [ ...selectedFiles ];
                                                                                temp.splice(index, 1);
                                                                                setSelectedFiles(temp);
                                                                            } }
                                                                            className="btn btn-danger btn-sm">Remove</button>
                                                                    </div>
                                                                );
                                                            }) }
                                                        </form>
                                                    </div>
                                                </>
                                            ) }
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </>
            );
        }

        ReactDOM.createRoot(
            document.getElementById("chat-app")
        ).render(<Chat />);
    </script>

    <style>
        .contact-name {
            position: relative;
            top: 50%;
        }
    </style>

@endsection