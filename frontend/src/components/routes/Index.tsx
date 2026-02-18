import { Routes, Route } from "react-router-dom"  // Ici c'est Routes, pas Router
import Home from "../../pages/home/home"

const Index = () => {
  return (
    <Routes>
      <Route path="/" element={<Home/>}/>
    </Routes>
  )
}

export default Index