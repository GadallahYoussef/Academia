import React from 'react'
import { useState } from 'react'
import  image1  from '../assets/image-1.jpeg'
import axios from 'axios';

const LoginPage = () => {

    const [username, setUsername] = useState('')
    const [password, setPassword] = useState('')
    const [emptyUsername, setEmptyUsername] = useState(false)
    const [emptyPassword, setEmptyPassword] = useState(false)
    const handleLogin = async (e) => {
        try {
            const response = await axios.post("http://localhost/academia/login.php", { username, password });
            console.log(response);
            if (response.data.status === 'success') {
                setUser({ name: response.data.name, grade: response.data.grade });
            } else {
                alert(response.data.message);
            }
        } catch (error) {
            console.error('There was an error logging in!', error);
        }
    };
    return (
        <div className='w-screen h-screen flex justify-center items-center bg-[#658cc2]'>
            <div className='flex w-2/3 h-3/4 rounded-md'>
                <div className='w-1/2 h-full bg-white  flex flex-col justify-center rounded-l-md p-5'>
                    <div className='text-center font-bold text-[30px] mb-10'>
                        <h1 className='text-[#054bb4]'>Welcome to Academia</h1>
                    </div>
                    
                    <form  className='flex flex-col mb-6'
                        onSubmit={(e) => {
                            e.preventDefault()
                            if(username == '' || password == ''){
                                username == '' && setEmptyUsername(true)
                                password == '' && setEmptyPassword(true)
                            }
                            handleLogin()
                        }}
                    >
                        <input type="text" placeholder='Username' 
                        onChange={(e) => setUsername(e.target.value)}
                        className='p-4 border-2 rounded-md outline-none focus:border-[#2e5caf] mb-2' />
                        <span className={`text-red-700 text-sm ${emptyUsername ? 'block' : 'hidden'}`}>Must enter a valid username</span>
                        <input type="password" placeholder='Password' 
                        onChange={(e) => setPassword(e.target.value)}
                        className='p-4 border-2 rounded-md outline-none focus:border-[#2e5caf] mt-2 mb-2' />
                        <span className={`text-red-700 text-sm ${emptyPassword ? 'block' : 'hidden'}`}>Must enter a correct password</span>
                        <input type="submit" value="Login" 
                        className='bg-[#054bb4] h-[50px] rounded-md text-white font-bold cursor-pointer mt-4' />
                    </form>

                </div>
                <div className='w-1/2 h-full '>
                    <img src={image1} alt="" className='w-full h-full rounded-r-md object-cover'/>
                </div>
            </div>
        </div>
    )
}

export default LoginPage