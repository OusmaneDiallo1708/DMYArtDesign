import { BrowserRouter, Route, Routes } from "react-router-dom";
import Index from "./components/routes/Index";

export default function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index/>}/>
        </Routes>
      </BrowserRouter>
    </>
  )
}