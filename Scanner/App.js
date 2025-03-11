import React from "react";
// import { NavigationContainer } from "@react-navigation/native";
// import { createStackNavigator } from "@react-navigation/stack";
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import Scanner from './components/Scanner';
import Login from './components/Login';
// import QR_Scan from "../screens/QR_Scan/qr_scan";

const HomeStack = createNativeStackNavigator();

const App = () => {
    return (
        <HomeStack.Navigator initialRouteName="login">
            <HomeStack.Screen
                name="login"
                component={Login}
                options={{ headerShown: false }} />
            <HomeStack.Screen
                name="scanner"
                component={Scanner}
                options={{ headerShown: false }} />
        </HomeStack.Navigator>
    )
}

export default App;