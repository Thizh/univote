import React from "react";
import { NavigationContainer } from "@react-navigation/native";
import { createStackNavigator } from "@react-navigation/stack";
import Scanner from './components/Scanner';
import Login from './components/Login';
// import QR_Scan from "../screens/QR_Scan/qr_scan";

const HomeStack = createStackNavigator();

const App = () => {
    return (
        <NavigationContainer>
            <HomeStack.Navigator initialRouteName="login">
                <HomeStack.Screen
                    name="login"
                    component={Login}
                    options={{ headerShown: false }} />
                <HomeStack.Screen
                    name="scanner"
                    component={Scanner}
                    options={{ headerShown: false }} />
                {/* A<@>******! */}
            </HomeStack.Navigator>
        </NavigationContainer>
    )
}

export default App;