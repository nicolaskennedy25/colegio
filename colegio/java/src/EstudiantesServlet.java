package com.colegio.servlets;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonArray;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

/**
 * EstudiantesServlet – API REST en Java (Jakarta Servlet)
 *
 * Endpoints:
 *  GET  /api/estudiantes          → lista todos
 *  GET  /api/estudiantes?id=X     → uno por id
 *  GET  /api/estudiantes?grado=X  → filtra por grado
 *
 * Requiere:
 *  - Tomcat 10+ (Jakarta EE)
 *  - mysql-connector-j.jar en /WEB-INF/lib
 *  - gson.jar en /WEB-INF/lib
 */
@WebServlet("/api/estudiantes")
public class EstudiantesServlet extends HttpServlet {

    // ── Configuración de BD ──────────────────────────────────────
    private static final String DB_URL  = "jdbc:mysql://localhost:3306/colegio_db?useSSL=false&serverTimezone=UTC&characterEncoding=UTF-8";
    private static final String DB_USER = "root";     // cambia esto
    private static final String DB_PASS = "";          // cambia esto

    private final Gson gson = new Gson();

    // ─────────────────────────────────────────────────────────────
    //  GET
    // ─────────────────────────────────────────────────────────────
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {

        resp.setContentType("application/json;charset=UTF-8");
        resp.setHeader("Access-Control-Allow-Origin", "*"); // CORS para desarrollo
        PrintWriter out = resp.getWriter();

        String idParam    = req.getParameter("id");
        String gradoParam = req.getParameter("grado");

        try (Connection conn = getConexion()) {

            if (idParam != null) {
                // ── Un estudiante por ID ──
                Estudiante est = buscarPorId(conn, Integer.parseInt(idParam));
                if (est != null) {
                    out.print(gson.toJson(est));
                } else {
                    resp.setStatus(HttpServletResponse.SC_NOT_FOUND);
                    out.print("{\"error\":\"Estudiante no encontrado\"}");
                }

            } else if (gradoParam != null) {
                // ── Filtrar por grado ──
                List<Estudiante> lista = listarPorGrado(conn, Integer.parseInt(gradoParam));
                out.print(gson.toJson(lista));

            } else {
                // ── Todos los estudiantes ──
                List<Estudiante> lista = listarTodos(conn);
                out.print(gson.toJson(lista));
            }

        } catch (SQLException e) {
            resp.setStatus(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
            JsonObject err = new JsonObject();
            err.addProperty("error", "Error de base de datos: " + e.getMessage());
            out.print(gson.toJson(err));
        } catch (NumberFormatException e) {
            resp.setStatus(HttpServletResponse.SC_BAD_REQUEST);
            out.print("{\"error\":\"Parámetro numérico inválido\"}");
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Métodos privados de acceso a BD
    // ─────────────────────────────────────────────────────────────

    private List<Estudiante> listarTodos(Connection conn) throws SQLException {
        List<Estudiante> lista = new ArrayList<>();
        String sql = "SELECT * FROM estudiantes ORDER BY apellidos ASC";
        try (PreparedStatement ps = conn.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            while (rs.next()) lista.add(mapear(rs));
        }
        return lista;
    }

    private List<Estudiante> listarPorGrado(Connection conn, int grado) throws SQLException {
        List<Estudiante> lista = new ArrayList<>();
        String sql = "SELECT * FROM estudiantes WHERE grado = ? ORDER BY apellidos ASC";
        try (PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, grado);
            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) lista.add(mapear(rs));
            }
        }
        return lista;
    }

    private Estudiante buscarPorId(Connection conn, int id) throws SQLException {
        String sql = "SELECT * FROM estudiantes WHERE id = ?";
        try (PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, id);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) return mapear(rs);
            }
        }
        return null;
    }

    private Estudiante mapear(ResultSet rs) throws SQLException {
        Estudiante e = new Estudiante();
        e.id            = rs.getInt("id");
        e.nombres       = rs.getString("nombres");
        e.apellidos     = rs.getString("apellidos");
        e.documento     = rs.getString("documento");
        e.fechaNac      = rs.getString("fecha_nac");
        e.genero        = rs.getString("genero");
        e.telefono      = rs.getString("telefono");
        e.grado         = rs.getInt("grado");
        e.grupo         = rs.getString("grupo");
        e.añoMatricula  = rs.getInt("año_matricula");
        e.estado        = rs.getString("estado");
        e.acudiente     = rs.getString("acudiente");
        e.telAcudiente  = rs.getString("tel_acudiente");
        e.direccion     = rs.getString("direccion");
        e.createdAt     = rs.getString("created_at");
        return e;
    }

    private Connection getConexion() throws SQLException {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
        } catch (ClassNotFoundException ex) {
            throw new SQLException("Driver MySQL no encontrado", ex);
        }
        return DriverManager.getConnection(DB_URL, DB_USER, DB_PASS);
    }

    // ─────────────────────────────────────────────────────────────
    //  POJO interno (Gson lo convierte a JSON automáticamente)
    // ─────────────────────────────────────────────────────────────
    static class Estudiante {
        int    id;
        String nombres, apellidos, documento, fechaNac, genero;
        String telefono, grupo, estado, acudiente, telAcudiente, direccion, createdAt;
        int    grado, añoMatricula;
    }
}
