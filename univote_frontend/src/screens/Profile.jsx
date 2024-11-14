import { useCallback } from "react";
import { useNavigate } from "react-router-dom";
import Cookies from "js-cookie";

const Profile = () => {

    const navigate = useNavigate();


    const logOut = useCallback(() => {
        Cookies.remove("user_id");
        Cookies.remove("isLoggedIn");
        navigate('/login');
    }, [navigate]);

    return (
        <button style={{ color: '#000', background: 'none', border: 'none', cursor: 'pointer' }} onClick={logOut}>
            Log Out
        </button>
    )
}

export default Profile;